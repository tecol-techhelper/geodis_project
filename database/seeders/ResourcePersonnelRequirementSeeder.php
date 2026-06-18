<?php

namespace Database\Seeders;

use App\Models\PersonnelRole;
use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ResourcePersonnelRequirementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $requirements = $this->requirementsByResourceCode();
        $resourceCodes = array_keys($requirements);
        $roleCodes = collect($requirements)
            ->flatten()
            ->unique()
            ->values()
            ->all();

        $resources = Resource::query()
            ->whereIn('resource_id', $resourceCodes)
            ->get(['id', 'resource_id'])
            ->keyBy('resource_id');

        $roles = PersonnelRole::query()
            ->whereIn('code', $roleCodes)
            ->get(['id', 'code'])
            ->keyBy('code');

        $missingResources = array_values(array_diff($resourceCodes, $resources->keys()->all()));
        $missingRoles = array_values(array_diff($roleCodes, $roles->keys()->all()));

        if ($missingResources !== [] || $missingRoles !== []) {
            throw new RuntimeException(sprintf(
                'ResourcePersonnelRequirementSeeder mismatch. Missing resources: [%s]. Missing roles: [%s].',
                implode(', ', $missingResources),
                implode(', ', $missingRoles),
            ));
        }

        foreach ($requirements as $resourceCode => $requiredRoleCodes) {
            $resource = $resources->get($resourceCode);
            $expectedRoleIds = [];

            foreach (array_values($requiredRoleCodes) as $index => $roleCode) {
                $role = $roles->get($roleCode);
                $expectedRoleIds[] = (int) $role->id;
                $attributes = [
                    'resource_id' => (int) $resource->id,
                    'personnel_role_id' => (int) $role->id,
                ];
                $values = [
                    'quantity_required' => 1,
                    'is_required' => true,
                    'sort_order' => ($index + 1) * 10,
                    'deleted_at' => null,
                    'updated_at' => $now,
                ];

                $existing = DB::table('resource_personnel_requirements')
                    ->where($attributes)
                    ->first();

                if ($existing) {
                    DB::table('resource_personnel_requirements')
                        ->where('id', $existing->id)
                        ->update($values);
                    continue;
                }

                DB::table('resource_personnel_requirements')->insert($attributes + $values + [
                    'created_at' => $now,
                ]);
            }

            $obsoleteQuery = DB::table('resource_personnel_requirements')
                ->where('resource_id', (int) $resource->id)
                ->whereNull('deleted_at');

            if ($expectedRoleIds !== []) {
                $obsoleteQuery->whereNotIn('personnel_role_id', $expectedRoleIds);
            }

            $obsoleteQuery->update([
                'deleted_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Values derived from "Maestro de Recursos - copia.xlsx", Hoja1.
     *
     * Every X in OPERADOR, APAREJADOR or CONDUCTOR maps to quantity_required = 1.
     *
     * @return array<string, array<int, string>>
     */
    private function requirementsByResourceCode(): array
    {
        return [
            'CA_EEPMT' => [],
            'CA_AETTC' => [],
            'CA_ECDTC' => [],
            'CA_IODCC' => [],
            'CA_REASS' => [],
            'CA_ETIOT' => [],
            'CA_DETCE' => [],
            'CA_TFFMN' => [],
            'CA_STCSR' => [],
            'CA_SCPTO' => [],
            'CA_EAOSF' => [],
            'CA_TACDC' => [],
            'CA_EADPO' => [],
            'CA_EACEO' => [],
            'CA_PVESV' => [],
            'I_CGTP10' => ['operator', 'rigger'],
            'I_CGTP15' => ['operator', 'rigger'],
            'I_CGTP20' => ['operator', 'rigger'],
            'I_CTC10T' => ['operator', 'rigger'],
            'I_CTC7TO' => ['operator', 'rigger'],
            'I_CH100T' => ['operator', 'rigger'],
            'I_CH200T' => ['operator', 'rigger'],
            'I_GTP35T' => ['operator', 'rigger'],
            'I_GO120T' => ['operator', 'rigger'],
            'I_GOA20T' => ['operator', 'rigger'],
            'I_GO200T' => ['operator', 'rigger'],
            'I_GOA40T' => ['operator', 'rigger'],
            'I_GOA70T' => ['operator', 'rigger'],
            'I_GOA90T' => ['operator', 'rigger'],
            'I_MCO10T' => ['operator', 'rigger'],
            'I_MCO35T' => ['operator', 'rigger'],
            'I_MCO05T' => ['operator', 'rigger'],
            'I_MCO07T' => ['operator', 'rigger'],
            'I_TLHCO0' => ['operator', 'rigger'],
            'I_CGT10M' => ['operator', 'rigger'],
            'I_CGT15M' => ['operator', 'rigger'],
            'I_CGT20M' => ['operator', 'rigger'],
            'I_CT10TM' => ['operator', 'rigger'],
            'I_CTC7TM' => ['operator', 'rigger'],
            'I_C100TM' => ['operator', 'rigger'],
            'I_C200TM' => ['operator', 'rigger'],
            'I_GT35TM' => ['operator', 'rigger'],
            'I_G120TM' => ['operator', 'rigger'],
            'I_GA20TM' => ['operator', 'rigger'],
            'I_G200TM' => ['operator', 'rigger'],
            'I_GA40TM' => ['operator', 'rigger'],
            'I_GA70TM' => ['operator', 'rigger'],
            'I_GA90TM' => ['operator', 'rigger'],
            'I_MC10TM' => ['operator'],
            'I_MC35TM' => ['operator'],
            'I_MC05TM' => ['operator'],
            'I_MC07TM' => ['operator'],
            'I_TLHCOM' => ['operator'],
            'O_SDMI00' => [],
            'O_ACOME0' => ['operator'],
            'O_AGCEIA' => ['operator'],
            'O_AACCCB' => [],
            'O_AACCCI' => [],
            'O_AACCCA' => [],
            'O_AACCBI' => [],
            'O_AADCYD' => ['operator'],
            'O_BPITYM' => [],
            'O_COC4X2' => ['operator'],
            'O_COS4X2' => [],
            'O_COC4X4' => ['operator'],
            'O_COS4X4' => [],
            'O_CDAD00' => ['operator'],
            'O_CPO20P' => [],
            'O_CPO40P' => [],
            'O_DCNT20' => ['operator'],
            'O_DCNT40' => ['operator'],
            'O_GCNT20' => ['operator'],
            'O_GSDT40' => ['operator'],
            'O_EM1X1M' => [],
            'O_EM1X1N' => [],
            'O_EM1X1B' => [],
            'O_IPCDV0' => ['operator'],
            'O_IHSE00' => ['operator'],
            'O_MDC55G' => ['operator'],
            'O_AACDP0' => ['operator'],
            'O_PMCT44' => [],
            'O_PMCT66' => [],
            'O_SLZVP0' => ['operator'],
            'O_SPVCM0' => ['operator'],
            'O_TCV20A' => ['operator'],
            'O_TCV20B' => ['operator'],
            'O_TCV40C' => ['operator'],
            'O_TCV40A' => ['operator'],
            'T_CTCM22' => ['driver'],
            'T_CTCM35' => ['driver'],
            'T_CTCMMM' => ['driver'],
            'T_CC4700' => ['driver'],
            'T_CC700K' => ['driver'],
            'T_CBTI30' => ['driver'],
            'T_CBT230' => ['driver'],
            'T_CBT340' => ['driver'],
            'T_CBT440' => ['driver'],
            'T_CBT540' => ['driver'],
            'T_CBT450' => ['driver'],
            'T_CGTPV0' => ['driver'],
            'T_CGTC10' => ['driver'],
            'T_CGTC15' => ['driver'],
            'T_CGTC20' => ['driver'],
            'T_EELDM0' => ['driver'],
            'T_EQEV00' => ['driver'],
            'T_SEGH8T' => ['driver'],
            'T_SEN8T0' => ['driver'],
            'T_TCC10G' => ['driver'],
            'T_TCC9GL' => ['driver'],
            'T_TCC30T' => ['driver'],
            'T_TCC35T' => ['driver'],
            'T_TCE25T' => ['driver'],
            'T_TCM17T' => ['driver'],
            'T_TCM22T' => ['driver'],
            'T_TCP30T' => ['driver'],
            'T_TCP35T' => ['driver'],
            'T_TEGH2T' => ['driver'],
            'T_TEGH4T' => ['driver'],
            'T_TUR01T' => ['driver'],
            'T_TUR02T' => ['driver'],
            'T_TUR03T' => ['driver'],
            'T_TUR04T' => ['driver'],
        ];
    }
}
