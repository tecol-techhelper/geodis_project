<?php

namespace Tests\Unit;

use App\Services\AzureGraphService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class AzureGraphServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.msgraph.tenant_id' => 'test-tenant',
            'services.msgraph.client_id' => 'test-client',
            'services.msgraph.client_secret' => 'test-secret',
        ]);
        Http::preventStrayRequests();
    }

    public static function rejectedResponses(): array
    {
        return [
            'unauthorized' => [401, ['error_description' => 'sensitive response'], 'HTTP 401'],
            'forbidden' => [403, ['error_description' => 'sensitive response'], 'HTTP 403'],
            'unavailable' => [503, ['error_description' => 'sensitive response'], 'HTTP 503'],
            'missing token' => [200, [], 'token de acceso'],
            'empty token' => [200, ['access_token' => ''], 'token de acceso'],
            'blank token' => [200, ['access_token' => '   '], 'token de acceso'],
            'invalid token type' => [200, ['access_token' => ['sensitive response']], 'token de acceso'],
        ];
    }

    #[DataProvider('rejectedResponses')]
    public function test_authentication_failure_throws_without_printing_the_response(int $status, array $body, string $message): void
    {
        Http::fake(['login.microsoftonline.com/*' => Http::response($body, $status)]);
        $this->expectOutputString('');
        try {
            app(AzureGraphService::class)->getAccessToken();
            $this->fail('An invalid token response must throw.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString($message, $exception->getMessage());
            $this->assertStringNotContainsString('sensitive response', $exception->getMessage());
            $this->assertStringNotContainsString('test-secret', $exception->getMessage());
        }
        Http::assertSentCount(1);
    }

    public function test_valid_token_is_returned_with_the_existing_client_credentials_request(): void
    {
        Http::fake(['login.microsoftonline.com/*' => Http::response(['access_token' => 'valid-token'])]);
        $this->assertSame('valid-token', app(AzureGraphService::class)->getAccessToken());
        Http::assertSent(fn ($request) => $request->method() === 'POST'
            && $request->url() === 'https://login.microsoftonline.com/test-tenant/oauth2/v2.0/token'
            && $request['grant_type'] === 'client_credentials'
            && $request['client_id'] === 'test-client'
            && $request['client_secret'] === 'test-secret'
            && $request['scope'] === 'https://graph.microsoft.com/.default');
        Http::assertSentCount(1);
    }
}
