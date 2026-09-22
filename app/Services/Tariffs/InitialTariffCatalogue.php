<?php

namespace App\Services\Tariffs;

use JsonException;
use RuntimeException;

class InitialTariffCatalogue
{
    /**
     * Tarifas iniciales incorporadas al código del proyecto.
     *
     * El contenido se conserva comprimido para mantener el seeder autocontenido
     * sin requerir el archivo Excel durante su ejecución.
     */
    private const COMPRESSED_CATALOGUE =
        'H4sIAAAAAAACCuV9245cR5bdryT4JAGa03HfEQPDQDVVzaZHIgmSEgZuNwYlsjCuQalKUyTb0zbmIwx/wXyAHwy/+VU/5rV2nHNiZ1UmmUmqNSi2Gi2Qqbyc' .
        'HbEva9//8D8eXP90fnP29uL66sHfPnj68OmTh6fPXj598eCrB6+ur16d//T2zYO//cODr3/+32ebp/2df7rGf9QXvr5489P11cUPF5cXr89e49UX5z+eXY03' .
        'nuGlb8/fbH3wuyt97x+/enBz/d/43X94cPLq+sefzn7+P2c/Xpxfvb3enG0uzzavzm7+8WzzxembV9eXb8++xCe9czmFNkUXfItRYsGLRUL1YcJ/iy7G3Cpe' .
        'y7lmCXHyrcbsYmj8sFRpeM9UYoq++pYffHX17vLyj1/94cGLd3i+P128ub7ZXG8eXl+9vbm+PHuNv70+33x7dnVx/cPN2Rt8R8qu4vd9EO/Et9YCXow+Z2lT' .
        'c7nUyBf5ayHk5FPGj+WMh2pN+Kg1ttTwrKkKHt6nOB7h5Kezm/N/Wn700c3P/+9s85vN9qsPeSJv7rx8/s/vLn66fsM/Xvz3s38635y8vniF6zy7fN8jVzfF' .
        'Wptkl/Y9b/M1ivdTC82FihNv43kfX7356fzVq4uf/+/V5vcvTkld9i75KTeJKSTvS9Kfx0Xg53HgsZVa9cdiyDk4mVot4oPL1fN+Ap5TSppi8CFWl4P9Mf2t' .
        '6yvS+D0Y7xe4DBCH309TrgFM43wWcxl/fvf67OrtObjh5N2/gLnP1vN/d775M/785pX+WYnB6chUfQ39Mar+YnEx4yxiLtLmFz1uPMc2ueadPkbVUxOpJYJq' .
        'MKwHa9aPeIzNs/Ob1xf4w831L3Iwe2/9xTmE9BJcRtb7/uLscvPFs7PL87f44S9/ibNoAUcw+ZxiCZAQIx+QytfvXr3Fz54dwt2fTupDKKPrq/O3Z5u0+Rf8' .
        'X1UYfxjq7IsXF1eb9YlIeU65pTjxu6GifJHMB6lSPB4kQGHF1GJV5nculNqmnFJOUENBmd+VlGtrU8C3OAmh7nuQ8KEHgei5jCvwCaKFH9ATwY/hy0FqwmlL' .
        'VIELFZxfQTyOupRQErVpa9BLEqYiqUA6U6oHnggeYvtBQDXufYJUx1oKDkSVtY/4LdwX/xcyroyP7KSBZSaXow94p0/KIJmXksAhDRfqRA48kTsPgksIVaaU' .
        'CtQurkJIPNRfSQnHJAJNE6AJyE20CtCLLZNTpVTyCBSDwHbgSHBFHldq7Mazp988fnL6YvP16ebbk69Pn59s0n95sPl7/ru/6fa/HhRXvZsqGbD/Ex/s/qKi' .
        'X1T2flHzUqhX12/K+kW/hRWFKXh2ffMW9vaSB/L46tXluz+fb17enEFn4z9QdXxLvXJ10e3tl3d+4wGeEKI54QbX5xzH//TJy9Mnp18/fb55doJHffq7xw8f' .
        'PznZBLfjaaHXcc2hQMGlD39F2v0VsNw4uMl8wcvnJ09ePHv6/OXpxnzX9ycPHz/d+L/nk7x4+dXmKf7/u+cb0HgDJQqrrkaS//WrzemTzTcnm+ffvTzZfP/8' .
        '4eZvNs++O30O3IM/PXz6Aq8+0W+/3vz2u9MnJ9+fPnn53fOTPZcBCwIhkkkSrFxtHrz+4YckrRs+5e8f7ntSfcveBz36MaGeg7gwuQQNAwWc8oNf4izxWE8/' .
        '6bGAC8QBmMEotOxh+v9Sp/dJjwnUCO0JHd9wfusdf/v0+8ffPP7PJ3w2SO/DkyenD09UkHPePDr55inEegdHFxi3MqW2iK9Rs6dv3l78cAYJfQ2tBkr95kdA' .
        '8D3qpMBmQZ0+MB80n/wquPd9uJVcJifFzf/4Bzt+H9/xoS8CmG2wrEk/fnJzfqbHf3b549mr86sF00Mnv7mmm7DgOEWzlLbrN9DkT1Qx/c3mt+/wkT/hE+/w' .
        '419sfnv9+hzvevXuh4vzm9kLgH0rU/arCm0PtmQRGNJbYP3LPc8Xm2f0k9785jGU548wOhfnv8YD/fb6H6/f/vxv0Nonl+evu4L/9zmaXU/yyxzK16cvZiF/' .
        'DmF6+Pjn//WEotuFFUZdH+CULgdA55ebh09eQvXgiZaXoAJ+87vne/jTt1QJNn2pQAL6LB/5m+ng3wyplJoUC2cjXAf95qPzK4jeSuY+qqrPtUCbQwWkj/zy' .
        '5F68/Po30KF7Tw7OnNQJ4LnqT7z47tvHTx6/eAlVSqxy+qLrusfQ1KcPoZmfbN2yj6EQOkXDBA8SfM4UF1v+x3/9ajsCgSf/T6e3ow+3Ywhbf998ff3DJfDN' .
        'u5urNTBxJ1Kx792/v77ZDldsxTUu6eG9eHt29fqHP/Nnr/90Qe9H5eI/pLz5ux9vv/of+6s2ugG78w/fXl+9PesRjfhV3rzEOxWp6i+/7kAVgNlXXOoEXpXl' .
        'tCJAcYXD0sE7/gnqKye6EXCUSljP1Qtd2TSVEOPMcWQSmC0/1RiiW1+E25sCoDm+crwoyTX4XhUAeLzo8So8owncnNYXg3ryFQ5Wi7PxImsAnBxIJhyhBi8g' .
        'ljTIxCv0N2DLWlt/HQ9dYFkSSDdkJlfhyOE59ShqSLVMpbpmCMyZrn1h7EH/IboXjQ7R94jqX8DfU/n0gzXxaBE/BqifOy7//tnjw2jKDEl5eCtVHyACCcLd' .
        '8FNyfhxmxI/CSRQIhT45MCNcNTirebynZSAz2NLlYpu6qfBBEtwSSs0DwemlNlUXpVOc9ctw+ZA0eNlBn4ceV5jo/xrt8/3J082jp88PJCngyQrYJM9aXOmC' .
        '++Zxdw2stnJjDHhCP8GxNNcE71IaiC3OUicOlwXfqqqHmHDcOOxgOE4anF8YjuTMAdymTpwnC+C7vGVAUvfs5DDqEniDcRec5aCODi9cnam2/kydCRsjUhPF' .
        'yzxQaAT8Du60oQ7vwdEUZTC8OSb4ksFAPCAlR8aE9esf03AYfNBIsQ8uFD14xinj1FLud9fR3aODxQtHJ0GmouFRXkkOkazQBavrD7BiaHzU3EUqgDUnBgI6' .
        'cSVksOmKTlWkSsYzOr/IS1aRSj6rfM6K5HDJevr88csD1WIWajsHMJFX1UAVkiTLhIdMQ8ByIs8xsvF+RsTtUoP5Fvm3RvLLVKFYjA5x+HrGAsLMnuVgRnwI' .
        'P+L5yTef03U9PPn5fz7dfPP426dPDiMLn6w4Fch6Tqt8MeCKy5mK9KiXCnmcfIrvuamq0UM+5lCIkDMoiZjSjCwoRhUuFWwi7EIdLwL6BYG2bDVqLBLWgk4P' .
        '3N9FVSkzArp8jUs7vc9XlgAGAp4CuNuq/G3wsZckBsRok3G0K1CDgmQMcUrSfUS9Mx9pnCb8iCorCA4OnGG+OcoakieyEKPSa8VNTSkvJjpr2kYzNNB73sIN' .
        'WNGWJoF2NnBDAKGhHlMou4DGfpiRBNc/Fe9XZZ5gcgoYBRfRyhqL9nhq73DIwfCdp1PbYP1iHS8CkOCNLc5afybP4T2gZFEU5LtSVIfX5uYXlSmKAMs1WPxq' .
        'Rc3R5OHkZ+0TdiGPvWasVWAjAvRSNR6eHKAHuCi41eiAfFxrmoixxs+C9zIYtORU1ncyVAsWCguzdUUYs5cyMblDaul0QB5huvt3dSiDp8dtZjzLOCw8C2FB' .
        'mBHdQthdDLJX8YMXqscpiqxAMQloDsVNAhi1/j4uEMIwReaVDN9AaSeg6Wiwa1LNwac1kofLyjC4EOohZEKbA8nLHXbPN7iT0AL7DVzHb7wNtrbhyH4wEiOj' .
        '7oK7II2w/KAINBro5aE0wLsTFbO5RV4EflpCMByVNCoUoxHCGmCqGGPXzIQ4MHIF+HHFEIefg7eAAysD6TBVF3EOjspuBw7ZL32F94Qn89FIH04aWgZ+zpB5' .
        'j9vENeF9onKH91QAabCZIRNOkNOMjgoclBAfM28LXIbmgYI1OvEYgbsDSPZaNohRZDQ6dnXhe+agOMBacGUH82p7oOwbHhsGyOgVSCcU4qQob4iKg3KD75XF' .
        'cCWwhm/MikTDlQkgBc/uWrXg/3Cu3IFN/lIKNDEZBgGaAyDh11WgexDLPmKBMSu8E1xgkpXYVmG3IYcupLTqmhhwh/AHCnMivDpYCqapvBUaUFiIQzWnhjfg' .
        'HdKJFdpnMG8VY9Ab4/E4K6ig8SWATvALJs2D7kUp91D+mOHEQ8iibncCFdlNVyQQghuA06zkiAzWJHdOpXVkFSOLCpjCM0ofggRzN9UlSBC6fGSYjFSi8c9a' .
        'BF+7yTOJur6zOoAAmM8WvPEEQCf9R3DeFlTJGaDX1VSsydtWmftIc/whDVuM+E12BQiWkVSoiFXeIpPZwqBHMURCe0SAwhQND5XEEGHGow5ua0wCgcgajRMq' .
        'cDehWGpdrIvyOm4VDMvzqFbeYAsIqVMzGPoWYNlHJO4BQhUnJi6WA85gzFBzmoBnVRwS63MYPc7dy4q8JXBoMHcKTOmg8kr15voafHCmV5foSVVcTTDE7NJW' .
        'RIHOvsBISjRiq5BhajXm90dN9lGXQF6JXbsrYeKBhAsUyLgoz0QScBGUtdGWDN4BZeUqhkVxa3hjTNk+OT6A48FN6eHQSEDL9oiggSk7qCtCWwKfrnwApuy9' .
        'O3wrKGP9Eq8pRyAwIEvgxVhGMKjR3uFigiLKAqPG8hZ7+EEqvKOJZtT6dJA5cBW+fLAqLrnhBNpsVZd4CX45w5mK3iAhuJms7wAsfE/UZL/oQWXjK+Gfru5P' .
        'dhJxLbDAVbFg19SgObEqAWLVhU6KVLiseTBcKJBawsk5ittZM+DJ0hTabA27NQdxDRq+tmScoCOE7g5o2cuYDe6WsMwor1FkfXYVDdcLElS3MOrEEg28X1nQ' .
        '40IaXMBgHobZTzxLK3okEC98/cTqDS23INNPTmQrbnI4Q+5AKJ+nxtyDUPYRy5BkywFIfHB9hmDgp5jFnQ9WK1YgSEIRFOm3hVfAj3H2CTUU4zzrlRJDyUa1' .
        'QJ26AFUpQ9HC6NfooISWhy8dsABwMucbiwUsLGdiaKCa+9wJW+6vICZ8Hjpw0dOrid8SQ+/20Adu6X5YSqv3SugJdQlMAxs1IGEDyGoTM2huRD0BdYk9g0pV' .
        'pAEJU1vSRf0SI0PzbYLTL91txJvgmEQNgAG2kBzekfSUUyzMIQ4XZQdg2U9NonLH87QOmrvfDOAJzAmVUdwqf01AWqB2zz2NAOsnuLw4gg8Rppt3ELbugMUh' .
        '5CnY1UGiV4aYGDDs9wQfnfmkZoQ7MHYgU4Y5sTmCbZSyjzJmnCQHhSCrmyMsX6Xig9mTRbMBEzLGRyeJn3NweUAngMDQSEAzCY67i0tURR86FQU1DFD2CAYU' .
        'M0iSXnQGMADwMOE3ekoAbNe08LntgyT7SYG1dEzClp7a61mWzDJBIvMeYeikBLgtYAY4D0oLThknCOVoaIErIDiB1Iv0Eix5gDBUGRoU/gR9CVxkslH4bYJK' .
        'Fk8kARbZh0L2EuSSo6s3sZhv+X6qP0d5ge7TY1budsKYBit2tAqSgAMPn0oeT0vQkTXBWZyFaFD18D63IGOIwPpwlGLX9HDFweITi5otgixg7mm+pkcHyxH+' .
        'B2YAnFr9TFgmB/WF75LuifVwtoCLPL7f1y5HrJSFWXXZyhFcWRY7+C05agzH55QNEvVMUxb47bVarXewNN2BH3vvjAkF4MeJH12BuXfC5Ccev80hV5cYHQBP' .
        'GeHJnoEVXOGIbQDrZrDdHIBZHhynxYoL8Fa/LqCUGmEuZwTyfgbcgTo+u+vaAzj2XlpkvwLsErR0XZ3hqL9DxdXViZ5mSCyyhX6aswc9fdBgn1jk1tyKjxIw' .
        'U4IvDa1hwh8gEoRO0oKYI4AzzcBKjR1xeAAfvfPuL0HjQMlA5qtlyF1Y4x7fI5M/YP7k74CNl+eX5//17Or15fkNa9bOfjp7xbaazbcXVxsvP75lidhdU0Af' .
        'i5kqKavzXejVRYhCy1lGhgluXIO4VhlPQ7e14hQIYgaQxDl5prKqDZPDMMRM3eiHbmzMAkHTyuyozr5cdGAyfqfx5aDLoRsmVnbYCAO06ceRHYnZ8JAtrQ0I' .
        'xLjE+FOaAVYnW6E6yFaDCP8StoIVCnO2UO+ZpQOO5RcGaXrPvzGmlRRjirYVxCUoP7sG/PEEk+s79KLbFACT5jJcQpOPIo8hSrAZ4GNdfQBYM5hHRhnmJEAP' .
        '8APsASRHpkTXC4QsQrynAhlWT5EO9CRrtKTnV8CHnhzb4zNQ01U8PZEebeCxJqrWap0rulw4kS0BXYDLxxHKoBHsBdOBy5Xhbir8qspLMnlENg+Q0Kxw0xcA' .
        'Osac2jgMuHJZPRhbBOUDzkIYNc3SHSLPwnM3R8CXkqod5JYKIMBAzxLT2II1H0UuQKR41j1Uw7YJv5LBaXFubelQxOF6HCPonUMhjbge+ovjmon3YBFqreZe' .
        'cZ8sCOe3Gi8wRHAKTjl1CWWElDUrBgbS64Y5ZcnEiLN8pGxmOAXsxChWNlsRlU0/XIhCPFk0cNhlU31yxhO7VOIbQIgUSx4MF0BuzGncVANug1y4aAvGPiSb' .
        'Heh8FIGZYT7W74GVVulkdwlMCFxTV0ZhWy0S6MqbNC1UCF2QiVrSsC5kFD5c7I6Dg3WZYKjtFQqkDsch0o7k2AUHfcaXaWHQR5EpWgQB3Za0jEVowSr+jmMO' .
        'plIIiDX0ChrVsjAqqmChgjzVbTMpBqYnHPgBX9wdXG1nhcFqI1zGYiVWy0hUuQxMZgAQBbfaoW0E9BndIEwcLDPrOeMWHnj6D1rTzGZE2ef91gaXLELxxTUa' .
        'yNJJjaEDu6QFiQRGtWkw4SAY75CmI02pziH42tNoCafR0pKa7C5jzTCHE6OfPfcbe/LOFggCRTI5FbwUC3ZgTKG8y9zCRZjzYbLgebPyrDi/QnM49dDJgOE2' .
        'XQmnXuj0AuOOB4FrnwuDWEuVZSfLMaoeYrUZ1yS4mIh77QApFEYa1zomALzAhk3tuwzsL3KJib18KzH0QXrgawCHgu+KLyNx4wm1BGyU3FIQFfjlGvsxBRxQ' .
        'pfDi03b9B84AXAuQknpQVNiHOOHzHZgntnwy/NKlNTVi4OK3UgqwgIF1mS5tgZcPUwP97ll+m+IabQfn49bAN7VVkw9vwvAtSArri6DWawgddAzHiRljYrxi' .
        'YplwcCsdaAI/63OwvQgmw9ly3F0EsoybVWkxuNsVOgAtHyQz5pp5pq52n6dXC0FdkLfw2CtACYSiDCJDIxr/sIbS4BsFZa0Enwj+D9MUhv3oOAUgael6UfVK' .
        'z/wYqWKrKCyg82UufYbegYvpRsHKh+UJ+AkssVVjCq0Xlb4wW+Q5SEZJnfgoQ3ScVMIhlzop0MngPMrYliQF3CfV9xwlozoAy1rceog8dSzyQYrwvXBI08SY' .
        '/JpO4xcBkbAAeUgUSwxZ0hXKcNbgqRXWL1I5rn56KgEnMsVZ5yw1GaxeiMDIYQ4/S3M0INrtfAzfLdDjc7osizU+SFf14PdC+5DWmmY2jwtj5fiZMpIQRViD' .
        'knr9Dx5WWKkPr2jkbVKlk6f10d1FKIz7Q3uXHn0O/NJabQNJ09qyieFik2eHqNXGkp20E2bcz9tKiZhiYlBxH6LYH8tk6MtzUEVzA1I0lrcCA/qB6AP0MCOS' .
        'VJQmoIkrZpHB0pqhKh9cEzSPEo12Z6EZm2+C1e7QmrlAoFjC935gIayrTkRo9S6w2J8uEAE+AvYKc9GOUodnSXCEW+35tblSKLCNCIzlByHwMtn4B52eetwX' .
        'bglr2GyGKlVNSC6huyVr0KgTGPO5e13gFpZQ5Za3ShcssthLEBAM+KmwrLH3BgmTu+ChOEqdYYdxyAHWR+JgPbzCeDTkYhTTshaDMx3yfM1zxhX4EHZJUg9q' .
        '4b4lazLS79WBgAGkF5572hEf+SBZ2XmWeLFGNFIHNKJ0njVDhr1rwnEWB4tBB++xcrZwFkLy8+AFsGf2W5hCpEf04LgYAgELk1ZL7dXq0WXqLVaOpd04Yn+g' .
        'GbcagJcI81e8xLAWIO0U5mLwjpcyaw1Z5G7RHw88aDf1GlhkcobZxJyzDTQzJw7nyzmTrwJnRXaeSct7QUUjoCc4Fltc/+hzk6hb2GIv80GlRMf6gTpHjOA4' .
        '4hyhuvOoaIJ8gR3Ao6P+M7PUGWaoaVcE/jM9H3zUJgPgsDmmCMRUZcDbJDphgqG+H9Pe4cI7qGL/PUGJ49oniHgc94SrCKyH9B2Mz64iBB0WzZkiQ9xTZdgs' .
        '1ZqNTwWlwwRKF5sExAsughcw1we7GqF0nG0SPOSadqKKvZfFIhnHyPscsu6+OE4dxh8/NBdqwanK2pvnh/GF4MFaFeZ3zR0S4LEGYWkU1TJYmmXGqYqVLIAS' .
        'djiGVuT9KIO20DOCwhKSXX059/P2BAaBYx58KXdQBkfYbB7dvNudn2KL/Trkq5sxzj6Dxw9UE1fUkYpWE06t9L722JNxeGyvOrK7Wa0ywgIvretFnYYTQ+4K' .
        'MbPiMc8zuGLUpshoYSG7QTjfwUu2YuaAOSJrY8SAi2OJYsczi49ZE78SxSbTnJhN7DfZiQqepYYwWCYNjB/v2XajAGBM2NoXTVyMdMbGqwSPG6MGrmPPE/RT' .
        'j6IDGsCIg0tSLyhMhWxSxN/BHMfSyalGkYOqoqzosLaihQzA5HFgYLyhslRZTMtz4qMAyaVgUhWANWA2cHbq4TgcReUAKt+hIxu3IsuM5i64yui/qybyxpxY' .
        'q1peuqtH+GgioUxwkIDqzY1QGy8GcqP1t6kP58KTsBLPmS51TkUKimW8X+8MLMgYoiyBwa5nSBZLwYIt16i1MYgYfNlLLHxUVzMDV2FHy/DxN5or89Numuu4' .
        'VKlWmHnAwDDR8I4bZTEQK22amNhUUXuZtzw2GDnqeraSG8sHvvSc8ZKSrfEqzNsQWhjFejjdjz5SWnk/Hq6LGyGFyIo20QpEWWOp7PPAr7Ywt151HweY2VG5' .
        'eBNSSI5NiwzfbUV9YOfdxK/pUR9XCkGD9i4xSRu1G8HUiXF2HmDEJEseuW7hmWNZGYJfWfYKtNnDnsKJSZG2QOu8os400/CuJiETUHhj0MMkwXFDNBdOvOmx' .
        'Csyvspkg2jS/47EysGJ7DcHn+Cz1/SATN8AI7AQJczsaju/tlYpjnmyCcve20hKWKzKJVfZGVI6kl5P1HGdPiA91FAfXir8lbcCK60kzGsMGVTWb8LaZ3YFC' .
        'LQMJsSaa5QFrD2t3vIDEAWXgMsmoRAXWomGtydpQhnEKDXWdAzZQ5oGP5neWz97f2902rIV/Z/Ohz1vjQ24Jaj5EUCMfskXtaxyOj7aPOCb363qf7DyDOJYa' .
        'RqSdoWqvZeFzuaZ2JkM0E2P3yaTPQVhVdd8nBXp2e7F7NphANHjLN/LIUtcT+qxTz5F/nGx4q5bleGoD8woBhq6OhljWAoPhSptYzb+wloftZAGOfb7CMSoa' .
        'cpAR6C3QYLDO8EK7f+1LBh84sQ0rlBQQCyfEDruI7LNfQpABpjtHDi7JcquL+XgyBfC5MZlY+qCUwMYzpuFc6uMsGRoIHLfXyrjLnHDDOjlkcCt1B92KtWa+' .
        'Z9zEs0UFnrSp++eY4KBBPRNC41zOmIlBxg+BU3xhwq6W8CHcdBi5wEe1MTHUOzV7CMlxREAS1uXo2FC2dvOilvhi7VU+WkmGSzMvVu32ZtO8KYWPLQIlMz05' .
        'SmAAkb3SvNU2vZNmiGrJbLdKaT9wOohaYE6Wq0DXpzFUADoBnhIriXtJUYQfyapqV3KveoFWhZRnNwc9e8ch88jwa2yvXuAsmko+lOHH4e7x3EztWmk9iM5H' .
        'HyenUB7Eri77MFL8jpWFomVoXf1DdKhza8lWRhvTyJwnaWWUsSxoWcvFRJqFajQ7gwGh5KCC5tnYrH6OTAw0v/SqMsqZXPY7EkpHUilBhz+AJaXruzynf+Co' .
        'soIs9dqFPleHb2Gjwjh/WFT6dJBX07oOixody+iWVJTMrV1QSOw32rKoBAtAu7YsCSfF2FGxne+cuZ05smgJBYb9mOl+3S5ne0Hnwu/qWbWgkznc3OK1ow/6' .
        '+DvmpK7EtEbrOYg+EZf9u1WxrcZw+ik3wDQ9lIEgWOQE+eKZ5PU6QSzuhw5gMfoJakmArzk+pKvopOoqGGsdAbNYIMWTGSEqGAnmp4Ikm0vcg5ru1/3eMrG1' .
        'FebRIELlPbgpHObgsEJcq3HDaDlizQouF7dQ4nqvUViqBFBjGnBg5TP77qAsRw4LLJErgxSlbvk7UNDsa+1RRkeglljlnHr7kWhhUVIK8Vv4g07JkVvdSMdT' .
        'SBoSdZ+pntTyFcfbSN2w0DdnfXYR03IAAMjhbRPLKpUuXCohkfNb/mapDD0tJkWZpOEEGmfYmeANvjfSleQQdL1FaDMO2K7ZvSesdBCNwk4ddv+22vIo/8Cv' .
        'ZeHdZsWqMUthSyOHLg0aWTHCSWTLKADtaHSVE5QkJbHt+sIWwCnbpH/wgTp58mAEg5bYxsbZM4bRWUPNsUa4j7RjCsxH0FzpdDkmhGWgJYAFMiXD/ivnFs/0' .
        'LnvODJOyj4TVzkucQDUS3sHsq/OmGZIj/QIRSWvW6rDnh7U16UN0s7YMai7H0D4QcDqMn2vjwITuI5smyCSs1IDElllFspx96kWm0LMkIJdq6MeFeU0fGZc1' .
        'RY7FudXK5GphqZfviOwYIh99pMBWbQuHSUtGYD1xXpyaq6NinVhYQ5/BaFkIKZ3VHI1zA/AMutiFMVoOOMuC9VHwxcVAxEoEyuUTtXcLcZjc5KuZU8LOVK4f' .
        'ER/ze+HTYYwM9MpkHuf89fSCznJikNp1+9ZbVBJ5mP9hgCTuNOHAZTERBjiygVXGsng6vcuJpXOceWmnCgRG2JnLbDYYDAVILCGmNSBxygyUNw32B5DTPbzi' .
        'Bh0WiGySYmPhxCWY6WUk/z7cdBCllasXWHbp5yLR1EsQM1tntKKgrhWmjIawxtGPUkyhQs8sujGNJ5VzFQojL5ZSgALH4gbnDQxOfVQUyB3k46uYsZq0Kzhz' .
        'nivb3OfigybvQU33725v2VycTGbQFeAi3mqjOoJAdmfDoZq2mtRAgfe+aHBET5mVrBTqaipxOAoR3i6bDUfwsHD7BpPXC8E9WVAZ65j6NpBVE3MAn84XNHcc' .
        'cdwMBS2/y+VHrOgto4r4GDzYUiFS92bqD8uxOGyNpc51/pEmOtfINCMUzj70mkcWE/vO3MuSUjMT1cCTmZfEziRDHNia+brUhxVAXCqOBdZXP1Kl7wSKvtwK' .
        'Kx2haWkHm1R1C9cJBsS1sJwJ96nf3SED6/jpmyQDlWD/2SXI3t4BlZhwYOAw2clGZF5m1LI5Cnx74yjN1vtOcZn4dnbB9Y463+cFte0g0hHEcblMYl5Yep6y' .
        'E1eJerzOx1ltCTmOZWsM1Q4vPGmWISxuWyeuaQ5ybb+TuRoa/geOq9hgUeXc5illE265RSNnOLMN1LW8Y+zMMcgPPML5dCkPpQhT7CFG3CTUU4J6jWQ+hv2C' .
        'dU8AvKloWRStTOFw7VMIMzjs7JZqpt4tzVQBAAlw4QNcmOKPo/HRsTLIGgwGvUYAEzKouzo815Z1GWQfeuY0KuN3cvwhNeUyvbMvRmN0fKJTaf0WRldZFmpG' .
        '5AbiZM4zzbYNjKuRhCekQfzQu0AmeBbhTsHQEZeIo60CfwAu8tp64VmqxTqoib+5DosN8KaZuVSLCAPH8AJXiw0+5YwZdginZMAsXmNciSkI44ZCgeAQfUnJ' .
        'zlzKbAuNuomAvS/aKcLxSjtya/f3Ivt2q0li7WWh2kLIwcZ1T1rtCJzDYYCRZUNlbghxnA6XNVHWq08KFSDnk46zqMwXQc2y9XaddItLFHwRPm3TpS3DlLD9' .
        'pGwNWdAagJhMESDnC3HedsoGsefM0Tf09kLemVK7t5d6y05Gx/w3/ATJ8VZsSEl8e/HT9ebZu0v8GzbhEDllszfjWxymtNrM5LjPj+N+fQdf2s8GJ5RTlPrA' .
        'KCFa4zBVH4eWFja/maE+c1OIbxTp3KJ1Tna96IUlSNxsMDQwTG1klXeapyOugOd4WukHaNwtjJiAZ3wkM/Dhm+qMjg/gAVbWvvUhqkVDY35p7ledhOvHV2Xf' .
        'TH8SvleYAS3J8vbuF2mdWcXWhqIC6oucyyvQwNv453ha4d65qswYRhghsXqMbEz3cQ3oZs0tESYNuABKG9N8IZvwSY01R3VH+yhp330XZ8zl7hc5Tr1MROfj' .
        'Wtnpygxk3m4is8DoeKrhzmdXveZ1RtIaz+KCziiX1EPYntlVps8GxYU5IU5/jL1xwcNhUzp6mBoOC4smZ4B766+76APOxWlPawVyuwuHjqePt8ZRrYqw147E' .
        '4plG5aC21qdHcbBKos4IZu3HPLK7mOlgsNLUxWx0GKPI2SdMFyOLnaK588XA0ro8UTMMJsZLnO6+Tla7hZE+RmxZX4rjFm9qORpbXXjAQVXwLLYs8GD6sNdy' .
        'wCNgh9oy5bPLbfT0WCRWk5VgNADOCfSDCZXsfvEQuTVo6WhycXd0eTm6q1sg1ci4n6CjXkqRXrORGLMBg4qpS/Nsn02wDsYvqawmZVFGtuons2ybEzC3Rjns' .
        'evEI5t6CUH8N93wHTB1NdMOxFuLkUrtFn4elVNh5dkI7P6oGYWqgfrn5YkUXlHTWG5Vl8q7Ma2SIvIP3NvGWBPp/YrPtjr+ydFHYpmUACVBybnCcE8t7duCp' .
        'z/GGGfphILU2f2tmsZKc3GHJVE5RzH0SgRogHi8jvn1QtNZleS4I8mIMUGUAgzmyaHKoBd82aYZj3XWBS+LwTXbulHmKDmPYOMZgI/ZchcE1uBoHZLULkFVs' .
        '85TBtI2lDqPLMYbJIZRm9hrgH1iPbXIStYoHPMOSZbY/V5OGgsxUbegdKV3h6K4IeF9sOoaDYBtXrUQTPGK5AagB4DR1g2QARZvRdNEkz17+iYONtiFUOiye' .
        'qyOF2RXVxt4jzhRwXBIR6xp7YDUD56e6aAptqmMpHJFFiWavAvQxhwQt05TUyDYW4zBuE3szs+fcYiahoi1AKlm7zZpt94Rzwpqo6sKOfrIjSG3wVdiuVJsb' .
        'RWaMLVVO/HR98KXvLcbCcpKt5QOMxThNsMbhrTCByAeWpVRH4RaYHHiFBTjWJ/ca5Y7BDFC8zbSZu0OoGrc3ZlgIdW8Fcjdtjz5/gTTY6CAiGxdAMfCZ552O' .
        '85Q/TkhlUCho/3mqdLNpwEzaErKUs4asZ9DaGyjZ2jlF74yPyhr/qtEMW5ldG5uXmYFIPUvGCDFXd4uGi7lXVVim0faFkT7fW7yDfA67y8qObw5eK8smqMS1' .
        'X25iQ7N2qnLiN4x6nAdIKJ6h9125LWiOSOvdAv4Kh61Gs/CQy9c5/dp7M0wXX5S0V6vnw4q2Fy3B7aSt9y1wGBira2c22YF2DrvLWKgJWE4uxu2mm8TlKq23' .
        'bPh5tmBKnMjb9z+xUAg3udU9IlT2lf2kZqBsYHSaI7miyeAG7rvhKGgx1do77UfmzCGOlZfYdkWQ5BAyQVpitVPTUr8eewTVjK24vnuh/1TRwoy05NF1SUYt' .
        'OsatLjPa0jyKjPfG5FaP6zMqNens/wG+8cHbDTKcw8+9pSBzhItL5koRYTpbbu2tPJzExhZ9DmpkY+RitT2Tdlr+pmmBhUzYQY7pcnnMv2winMjD6hUTRnRM' .
        'mnJWX5+OnBkm49jaatK3HDIVOImomkFgbAsL3Awpw6PPHDtY2Dc9ZxvjNuA58CbpZHqmx2LuKVVYL2HBbMh5JVGX3HoG9kwVG2d5ssGARSH6d9wBZ2DWHoUQ' .
        'rtvjUrGuv7g6hl0eti5fk8FsZbJRwEIdxYHraQZR8S7COYw2JnS4XoZV72leYsb5OlxBvtZRcY8c50FVZ1q0KCSNoAUQoLe3ZbZnQRSNpgS3+tDbPg1oA+NG' .
        'Dmyyw69208kiOA7HznNhcrwLbw68w8Sxh3TfQpvT4ix2Bp2mDjpzn1uuuhR+RD+ZTBPu34ka9gI7RNo4v1VYK6y2zrB0Yj0lGBnubG2W+CPofHSkOHLpEaeN' .
        'tx5rmsWRu0I4mDxpmCTzyoWz+tsY7wBR5G7CibOJDeFeAWBxuddTJU7JY+u0mYPqEgeQT1uhexYek62hheooTuUuAl1Ws4ZLb0Gdwy6Ss9w5N36CbkxrAMYV' .
        'OhAsJewlBFqACh2qytA2BnpNhwqLHrOddAn0y8FeWyN7KY640TkSXnpmBQcM25yLXeLMwe/wXopZ6NCNNgV/a5r8FvT5vJXsHfhz2P1GqEjW8k2MU/faQ66C' .
        'E8fuv8VsFqaagmIgb2s0uLmRKQnp61fAiNw77LVqD8eXpeosz5Hl5oVBOnSNi20lqZwdB+6VseaBzewcQZPmec0782f3TFALtwlBEfcSZLbXsjehxHXtzBbu' .
        'aYdZlMbGa88pTX1iYagcgIWHhoDkVTjhqoAtdaTSypZeWNhAwxBMT1fQOc0hqOOYgIE0O7NMLNBbLFyfQwY2rVqNRfX8Celj1rvgMJTFkns4l34b8hxIHdO8' .
        'PurG7j6Dr3B3JAuDssS1hxtsyRCZD9kwUKFdZV1SG4kJtjtBErkXzfTyM2p3q8E/lgBcUCZd7bqiX+6T1BopKwdcnMBIJUQ3bs8ePIJQbq2FhcKhul6NBLkH' .
        'CUG32K06FjetDZ6tZSOHkFcoP1aSN7s6Wutqc+xt/L3UqZQQzEoPHA/npYkxovx+0cinZJOR5Do+ejFhSQNa6HMYhXgGKNSaAf7neG+F3Qqsg0xuoRCeSkla' .
        'xObNVSYWGjmO+Awmx1R1gLCLZq4Cm8oa57KU0nvdIdXU5W4r+LWLQu79cmxIS/MGyrvI58CLZFl/ZjNtD6twhywX43JucjF1/lpzqItQVzID51By4UAp1fSn' .
        'NR3u3icM6AwNthbMO1k0u7/Wuh1L3qNjhZG8qG5M8mvnaeFaVCCN0GH0LJCFE67dgk373BdwH+EedELvrXQ6BT0mA+zoFnM3nuRi1rMUlhpq7ttYDWp17sPu' .
        'fb8EFSWxKirJzhqhw0iEW1F1r0zow7m7t17AP0ndcZ1X0SeeFpZxjZEHfcgT1x+4Xm/Ry277HKRo3V1Od+KSElvUB1e1seGZE9NGhRnkQzjgUGpKIx7KSAHM' .
        'BtzdsifR9bnr1jtY5zCClVq4kFzLtKZ1mICpXvWsAiAILwOj7P804+vxsUhdmfwck2biiFyqrb4w8pGD8ap59Ez/DFCHEyfHwXFbqrb0pDLvQOIYjilLDXtB' .
        'zj0UTihxYoRJJJshj/xjYLGLvxXh8eEw4eRsWi2SzX1rC5vW+JVgj7AKZtWNb7ecZ52EzuEtaR7GxuVUurGvL4Fs8L4nWYhVieQh8UBcMHqU1d1NQW5XO+I5' .
        'yEarkrbhzaEkcUEk4bdkWd0r+FbcdsjkQF34lDaa24JSsTNqky4I5XCHWd+w/pTqJ4+AVOYyE3V26/Cy4cUAELCteY7k6Rp7Nj5Gyvq8SGSGcPByWEIO724b' .
        '2RxIo+6DAJLmWGBZ8bHOQGR+SdpYFM3/Mf1jiq2Y6qq60sLnedwQHA3GFU2UNXPEvVCsJI95EZq44sCNOsrcG2epsFulzI3ncf4Nrv6Z2EZ3F98cSmcI2uQH' .
        'TyD0LJMLmgSYao8a9iU2xTH0Sx/YzDiEsLG8nU7IGL/DPbiJYxSHDEHEdc62xGKmNkND0vMqdgbfbkKJCbjXPM+75Xfkrw7lXCEkZLqt2zpOQIgOjzBxmMi6' .
        'Z4kVXOU2tSChcIx5SGKGDTGMzB3juuCTXfNsLqtmBA0RX6X69qYc8Rg6Hx0tnZFdpJr0d+v0TMbAuUEhBw1LSJfOgodjB0owzg+eQBiNnIv/O/G02lwNX2fh' .
        '5GYYWAkXjOrx0L6BvW0y9gRyNAUXoYhrW3PEVE97hhNvIZ4DiRT2L0NCdQD6CCkBvcbE59RUo15m40w7DnLIyWzRcUGTO23ZHdGn7xV2NMFzNJkheqnaYW8B' .
        'e+DjZ46KNpX1uqecStGVZAoZ2edduG4i7ME991LpctOLTpJLtregQiVkOFwyJyp2454DCa4sruSMRHj1a7YHULaykZWFwHmJd7DViGt1y9YCPcgB1CtHPw0Y' .
        'ihtj2hleYVoGp3AHbjV9X6yj1F12ffwtwDXXwrPtsBdYM2AUAqPzZdd45/sqqbvNaOGUsMJBs8sck9EDdgiJkCdouMxxbm6MFoL3UemqgFHC7DLgifpYf1OG' .
        'jxvO3Jo0byjuk7+4Fpf7wXorD0dJTWlZYax3SjvGcbzO7ncR8AC5P/te0185/bJv47jVOH8EcRyTAnjGmSrzDE0ljstkaAKB03oclRE6+FOcKNN3o9H9KtOy' .
        'Dj7MLWtcTeqyGY6Jr0lcCLflXhUFyiyrN2lLzwBE49Rx58aMZq4bZckiByTebgM76PLgeHBCblcla7KGzCgc2hC09rnvL3OMmAOpzlLiXWVBBsc19IQRPQiO' .
        'p6jO1IXAnAbNc0XT+ofTYQZmjibMa8Z0kAUDer0SHr5LZK95KcvEiR3NYIeRyL3bSUvi4phtCmMv7FyOfQjVMgBWR8BD98d5+DrgW+YQxT5oEc8ISYm97kBY' .
        'AcqddaGOKkN4FwGfmoBJjS26RVzkDDFucGRz1d7+r8OEr5XIygZuLQ3rKhPHidDMaYW83h9rlfgMHFnVxayPP2Lfe2/jb8ztlWLdpYwriHSd/TAMQECMG0Fo' .
        '6/7720Pio6PFr/LIIX41rBCd3rJWpXO0SBc/TuKA3x5mo9ZlkF5h1vbNoVtYJsDhvaWXf1CRCutEzdAs9i6we1VkhEE852uxM8Ets4B7jlQ4mhRAXmRP/9dB' .
        'l8hATuLUVgbM1pmtvC+Gy8VAb8+sReImkdpj6kWnOkHRtDEQPHJtLjfEa8FW1W060O+ljNgsVx/olIy41VQLMiH7TGmuU164Ojkxg5WL2xfWuX+6FDCLKMaG' .
        'q3G/MDWS1nyJ39cEdtCdJs0NClvp+pBy4Voo9STcGhOAguG6TaaGQ2+2LJxtSa2X5wbTChnkNN8RpWIfvDblLRV4aigBkLjhHW8XM+uA5iHqeKAe+oHPw9yV' .
        'zO5o2N37db9Ec6dhhIaKHIXGvNKtMdMXlxdXr2+u32x+f/H65ud/e3d58Qp/Of3nd6zRfn2+eXT29vyay8FwCm/PLjdffHtx9eXG91Pp61ChcYmz5xHgvXDJ' .
        'cXIyd0u43jkD35orZFy2rcX9o/CKpBgHSm2trMNb54m9XjSK3NFM5KhGVnDLvNvCORaPmh2MQXcWZDvmbZ0U9OkkgwyCREbjzMRdAADugmSAdkznYKwA1FTT' .
        'UNM/znSHqUnn3BkmVJzdnNMC19Yz5tnJdqx3J1A3PfiFY7xhuHo1fuLaKtj2MTPok6llRRrLWQlxRq6nZo7DIPdlP3ahcSElkK+ZGzJ/fOKEvlEHQFTApHOT' .
        '7QnxGudeUmuM8bN4IYmd6M9xWpNPdrRvqVzTxgVDbte8xU8/Ae7RY4AoLmHK5rWwpfXsKye5srujmPzB/KGJ9RijzwZXn6jct+ryoDigJybOjzUTOjhTJbLD' .
        'agCQo6nnHKFPpl7HmwdGiUMYAp7ZJ8iu+pDqKGtiGSPsiCsG9OrH2e9ovGloBdqWtDWh2XEGLqODtZ9A0PErMEPG42bIGs+Smx2VBs1SONi/mH1eny7k0Nwc' .
        '7x7ncx1jExzvJa91NvTu+FJdWh7r+nEOXbBdrlzdzIbJra3kzJZ6hsuMgHDYbu3bEs30sPeI+jxj6FOp5uTfzBBnnft55h4y6jnHnZQd7/WlwpxiOy0LxDt/' .
        '68f1YmUtuK2cAJU1x23HtNMBdsoCowcH7/FcT7gFT45g+nXw0F+Nit8aQvSpVFOrsQCA5cbR7ITmWMXiNbm4xngdh+ABVZthjv3jifPERoyX4w6Fo/KibWNn' .
        'BaRuXKrm9oERiP5KyM7M7YmcATCVDuEYqvFc+1n3zHL8KxP7T4BwYVw7JRHsxlKpMSFGcG3ClRSlw+2+UqJy3wxL5NIopusfZ6ipH5m+qGsagWmX3vU0zxhm' .
        'ENWvi6d7/M9XDkjItlrm18F09gwcV53qBvMxDVLI+JnYp8mq+Ng2qNMS6zBo88eJctzoNAA+5cqwmGVrgkNLLKNvwRnWZ1U8xwrJ1qqYvwSyMzQXeGM63XzO' .
        'Z/XtSlGHPvOS+wYwTq7ZHqvSPwiHs83hrbneLjGeyoFjfQaHljP75QZ7YwHrpaAsi6mG/hUxnaFduD7BK3d7u7idRQ/CzoO556H23bhhBHfmj06tNFOl5zQ2' .
        'O8GT7tRnOjATJwaYlTh03qHcva1Z+PUwnaEejnDA1a+Guks8K7Kp1cNcftSTlplxsZLmRqEyPs6cp6ksbCxwpMc6T5iDqGZoR7PuKbTKGVG8/mYXqf1FIZ2V' .
        'cc/JFjrHswyqOR+f6/9YHaLynUL3O3wyZZP60Ykx9MEIcII86ySWYdl5nh0fVL6jNW0s7udgfW9M218Kzg2KPdsJC6fewKyokLYC9cWYWZyHZGlhBUcCCEuJ' .
        'bWdN/+iU5ngp539QEEzvEKtWWA219H73xd4c4MREo5d/Dwx3b1X6L4DkzMUDVWS4YzovW+aOPW5eAHZKbUkuZqh5vuSX0tJkPko0EOeSJVFVFns+sHg2PHLd' .
        'wtBsrBUAtqVXbxdA/ArQ7f6J9x//9Y//H86LMEFUuwAA';

    /** @return array<int, array{operation: string, concepts: array<int, string>, rows: array<int, array<int, ?string>}> */
    public function tables(): array
    {
        $payload = base64_decode(self::COMPRESSED_CATALOGUE, true);
        $json = $payload === false ? false : gzdecode($payload);

        if ($json === false) {
            throw new RuntimeException('No fue posible leer el catálogo interno de tarifas.');
        }

        try {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('El catálogo interno de tarifas no es válido.', previous: $exception);
        }
    }
}
