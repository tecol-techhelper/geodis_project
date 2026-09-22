<?php

namespace App\Services\Tariffs;

use JsonException;
use RuntimeException;

class InitialTransportTariffCatalogue
{
    /**
     * Catálogo inicial de Transporte incorporado al código desde tarifario.xlsx.
     * El seeder no requiere el archivo fuente durante su ejecución.
     */
    private const COMPRESSED_ROWS =
        'H4sIAAAAAAAEAO19265l13Hdrxz0UwJIG/N+8Vur1aA7IdkyRenBthAwMgExYEiBsgwbhj8in5APyFM+QT+WMapqrtte+3YunW6dQxjWWrvPOXutmjVr1mXU' .
        'qH/491d/+vanf/nu99+++ptXv/3q/X978/qLd++/fPv167v0r+nuzeuvPnt9V527+6+fvfrZqz/+hB/806u/+YdXMZRQ6yEVZ/8F/LMv0cdDq7z0KbiDS7x0' .
        '/MkecdlD6odY3fRfw4e1lXAo/KUSujvEgqscUz40/iu+oR1a7tN//MHkUzn0jqtYYzx4/kotrhzkD/rac84uH1LSb/H4sKWQXD90XuPfEv6A55/KrZdWD7Gt' .
        'rn/3Hz87FsxXX717+xVE8ot3X77+5ftdqdTQatfn8a2keOhyGV32h+hFLhVvlqLIJVd3yJkP11s++DC/JKVZfWuHzucq3rsDP8qh1EPgn0w1uYPKIhR+0EoP' .
        'h8yvCPiylnM4xPHXKPkSSu4m3Fpzh3in7+OHpcZWvH3fdL0jh69/89Uv3t/5u69//GH17sn37MKh6iP0iheS1U+lxUPpIobg8alIofWoa9GzT4eFRrgqa+mh' .
        'EhRSSZSRvDse257fQbUS/2LCmx7CQm5FlAyKUPv8F/mTIXaXQ4JQ6iwU7/DM3R+SyrjmJJfVR/zfoeTV9UlRhGNRlOId/paIImLxDyFutseuVKAQXr+2pVwP' .
        'VR8Ae0KEUnyvqi57osAuC/3Q5F1LLnhVf8gi6hh6iIcqL1mxLngE/t0eHfZAS6vrky959+bHH+6+/f7bf/nmn3786e4P3/3TT3/533/+/rvf/3j3b3efffPP' .
        'P979LT76Rj/ZEQgWlLtTV6JBICqPUuIhyJZNvbeDlweOCe+pr4THPZh2x6r6UmPy+tQlhnbIsm8L1Fn+TvahqNyxACmpOofqoRLFHZqb7YH3udB2TJuE31h6' .
        'cthXTpYAOuz576vrkwKKxy/d8NfwXFy7kKjHsnM9nqYfspu+V94/Jw9rKhsmtpoO8pPY9u7gxXq15tSAQEm7SK9kB+EkMVu5qLHMgbtFjQv28EG+uraWKuzP' .
        '+u2xAUM4dNP3jO2Yed1igJFRCzZfn3zrdPzWHW9d9A/D2uN5oqxqTRXWzG/eukavS518bqbIUNiitqM7LFoq8/bmm9XqvG2IglcU9ci9RrU8OWJrZD12cAro' .
        'IRFaSj1A16hm0+YP0DTKNyzkAvtNBQiy6KlDVdX4zNePskOOxYaNXBN2AL8sZBgd02Kx1GIIIKAIuybnbArYxanpOQujm8XueSxonfVKtwuNWlZptaS6UZwv' .
        'okJQuqQnLzQthkOSb8e5lXlI6SkWgq/UvbyUEmxaafrjPGl4lsbV9Y6Ufv32yzfvPv/8/V17AnuJR8SBEcTEQTQxrO1l4M+Fk/YSYqglrf2FB9vN6X1v0Isd' .
        '0bRQSxFzgMeEvkeVDDwVuFTzWouCyMEnIiqtyNNiE8BciLuB4x6/bW8DIWQ5XXF+ioHJKdvPZexRKNR8fOpmooUQjQvQhIJTydbIV9iRpopVXezQUdk8AZbY' .
        'qT82X++6Vb94+/fvv3579/VXr998DWmJ/3n387sv3n357u6L33z++i4cHyit5BCwu7mmMYp/uTYtAauX9NXgjJSsCgqPqnTbQ7DAUH7dQ1hI3fMd4tbtRqN5' .
        '7JdAv9Xrqr6YXkLxEi9jSq1W/HttizWB2Q/JDmsYw1hMTalersEfdcsVPP7weonFfCSlDu8YayOPHgt9UKcrGGLWfRN4Nh7S/JYqsIQzVs/lEqH36twHF9PY' .
        'O45LLu+UoWk5zYooqubxS7mL+YHo5fthvuiR8J/xVCVDsbG3FrFD7thvdPVV3XzE4Rz0i+HQwJSHtL7Zs8QriVCDRIF8PVag7lKzwzUmhiUhTkKIokAdx7Be' .
        '4pxSg+l7wlFbZ3npk2eIM9sCwkEXzegJR5LbCKbjWMbO2yhVhaMi27pGeM1JjT0lrE8nzhxPgTo/nYd8XMzqMUDjqYWqynALcHj4uFKrow+vl93O5sNhi32u' .
        'tiTWBKM07z3ZHIgN6QLIZRQFETOLtasHJ6dK6YvlH3IMGTvFQiPKeREoSqTQcZRF9Y6LeojNlaRHAWItt3WqIhywFhCEpb6MUTNcY6cnLcyjr7UtQi95DqwD' .
        'NkAO65uLMvvV56+/fPO3r++iOxYZnrRb+AUPBdbzSGTwPPXcCrHCStT531V4cAvhS64tfpXAcA6ORQXwp5ptFpdj16OKW7Yv7Zl4lRmf9Y06No9Tr5moYDIk' .
        'poIo4WN5PsFCCeG/I/rEo6ooe4Og1FcJLXruqbK+uShCBtnv/57R9Y4QvWupYTm95AW82FpxMxHx1UMrqnkQk9c4o8Cl3IgRbi90UD0nHKdOTTcOAGy8qj63' .
        'g2rLS/QUy1GyApF6MT8db+URsqwkmiIEzTNmmET583joTBddbgJODuh5We1QBs/01+P65nqVOzb+sEXJ00uTdAEOHq8PAPcqmktJl9Nb0iAxGBK59ErnQf1M' .
        'hAAqbigFjnB1PmCqzfmDMDRkhiuCxZD3655LxB+EocvYgTDWfuFXYQFwYuvpgd2FU6Vb2gaL6+iFra5vUZk9IbSYigWHCepc1eWLnkmnbP4uFl82ScjNHakM' .
        'TBmWa7vJYPOj/inYDgaaurTOMijwLhlRxZVywAhEjYGx+Xm4rM0VXAW4cCWr7IODJmIhdD/BxUFUYA4wzk64HebRzDcnJfVzk9Tbf/3nb3/403f//ftv78KO' .
        'qELBwibdHFg1BBOypSI01atlwlPkoGc4XhpOl4QIEA1CJr0M9J+2UV6XEKVNslDbwu3b9RUc/moQ5Yh5JPbg9lKYGq9AFWChp0UQJ6ZBIHAzuh4nMP2wmaa/' .
        'lTlDPWjmm11P6ovXd794/V9e33397lfv797tGp1H0CA4Wbb5oP20PxsB4TRsttCJScOmOw7fpqGbl+8KGlJzT3eL3kqjKUmmR7ICMEpMVeSy+gIE0thzKu6G' .
        'Ew0hlu65+eYK+ewL6KPVG8bCiJPS5N/KY8H44CwbxgobkWunrgpCzxo0Fby4uUYw7+7SjmQQElS683wuKA7DczlLGW6Y59mxVho+IKiPGq7iFfhi8hgpwumW' .
        'SAt2qJmUEZHT29lKxjHQECWi/x6rihthUtXEUUFMwzxQn00SzqUoRktWwVOp3ewRiHIjqvHVjYTOdHOFVH67KxSGlpCEBPsSZaq6iFcsvlyi14zTePV2EfG5' .
        '04MXMRJUyJkR0PQaDtOoS0oxBQ3m8DI+aQIPasOEuIgcy6KFCYRr+DUIbWwWObR4QEf9WRyKoY98fmgtY400elzcXBbEvhxKjqxYiEkvuHQjsU9HUYIzeP1Y' .
        'Zl0nSYAWjTOxdJr3hE3EqV7k/M4pWt4jeOYtitlC/IXe14oCjWceYSNfT7GtY6DONA832Uiay1NFJh8Qe4+fFMWAe4EfghFbbGB4GsxeaPi4uLlCYu/ufr48' .
        'rvKx/GDosoOtFJkwmnOaMyxF0lVSxnBUbbGgGTbSa7Y0iYGYsn76psk3FlvmcE0kDVXN6vNAEN20Ci/Bjaz6we0ip0523LMa6UbEbr10mLk2bbSCIIzVrbSU' .
        'Wiz4qWpZiBSaT3AQil8+1/GHe9L75n9+9+MPd5/99Odv7ryI6u4/Mev0/o/f/iRZp3+7e/3Hb3769n/w5j/bj6ylmXGaMkeTlgYYq5o1geMQcEmsKoUhGqEf' .
        '/vz999f8P/xy7rmKcBaqARuYYZl1F0M2CP40lYuzEtsxmTs/31x67Xz5tY99HsTQDrvFfIXMnJCk2hDJwDGWA7dh50WJFq9/Y8SdnSnHKXUtGkZrE6wGEnAu' .
        'QLdWpzTCmCQHaVnfnHjzv/xfe/VwecXD8Yqz0BGyVUyDg7u5ijlFj6HJTW0KPBDPcm+/QQxMKSeIwOk7w0/t9Ggmp1fjNZxIuWjGPiKIgBnSHbu4ubD0X3/3' .
        'xx/vfvX9Nz/8/g/46Lff/uEv/+f3f/7+m5/uVu8rOlzNFnnqnhy1jg6dvCHc6KKyR1yLAN2ZPwZ3w1kYy7hOdrgTz0XTPAihtxkdaE8dRWmY7WoRwyiv0gaU' .
        'inerfiFt7AgfJ4dPQzP9TTxu7CMnPt/siOXt3/2G5vPtr3/19s2715/fff7uy7ev73759u6L97/8zeevv1pK5Np1PL3A3J2ZZk4zMDit4FeJEQ4MzusoZ8w3' .
        'Vzzyb9999vrXj/ucOPwb/FyrxWOJIoyNpiZiio7HbVjfXBd0fv36y7/7zdu7/jOs591nn6+eGn+ttwAtyQpnYJZJfDe4fNx1cmbLqaXRXvFRU4xyBJtD6uDX' .
        'qPPmGSerTURoDJXTBEyk92JJUm5gp3WCBJdWviE3+IxV6j6LcASHtHgeI8dTi9VefI+SVdne3CQO736W9+SRszg5WobRcop6w40V19n1lw8DgQ5qmhZCysz8' .
        'yrYLOHqbhcPd+5HIR6RuVWZKqarTSilly+Rix3k9xkv28GKwo5fRE4753Hyz32swQfhx02hE2dAh9arnmyPRvLkWQRN6kZyMeuwteqtpRex7OQnhmrI6vjUt' .
        'MMlldl4UQeOT2uZXmWZFQiJEYhZ5wLcxuwUtdBpXSAI3a8kb0uuG3gkMAPTcQwxCCXqFWUAQRU8CriIcPfFf5utdMVyBl4mtNzPKiIJZ2V5kzPXcoXJqTpj2' .
        'WwIkqG/XxW+S4ZLlhFcvywYdsd+A5ld9NW5sVRE4Hk29w9qnZE3Db7OI1cPsELcKB9+qo3CO3HjMwlqW1105X++8/ymcDNY3VotAfGcdtZqSNoMIYSsnk4lr' .
        '2NXidkMO3dIheEzVw9ygKxpdOqysRCZpcqsSiw3y0i1Cz+VvB5brHEyNXwi5h0JZKBwKp1SyUKRgj0Url87XJ191B/YRYoUFC3oKMBLVnLqAPXSbYr2bRjCI' .
        'uwzZ0KvX2hHMWFGfnssnOQ+61erR44hUswA3IWsMTfTGYYEG6uqks7qLuDdoZQn/PvK+hUixJo4A7GvHz0gOeL4++a4PhMNEBK7RIklEHubt4mSH36EmS1RX' .
        'pMINL34AvMeicV/trM8pagsyk1+G3TKQXQ6RYdZsIjTqIaJqUeiLuo+gPwtsnWaCmfbPbsKHaT4zSxFMfq1gN1metiJudJZ4m69Pim0HJENkhjf7HmhlsiFD' .
        'Uh1JaOjsqCRUHACjfI+1blptLFY5KZ3npYRjrC9JJS3HFs3tagQ1bEXQahh1bQKP2nB4ufNcnDYCkzeSSWAaJVmkN1+ffOMdgAxiUWfGNATBqKj/hBBzQOZS' .
        'GQnySKyGpupc8gbRaJF1G5G/a5YhENnLqydGqnzfztKXvoyksixBCUetcrcvMgI4dRgfy0aF89uIGREIYmUyRP/IfP0ou2JPMNWNAhIOImx5S914KJ5uXsQP' .
        'bThreGazlw6npxyeLfL0ki3sWaAWw0UrLtsaDojTLC3WmdCpWe/VMYSmHRQ/5whHSjCSbgrgCcaqzsyoFL4M4AgzRbWVKsl8vSOjcwCYhxlKQX3Jy9JtihtD' .
        'KdmyrTm4YDN9zyENSM6DbeYjQWHgKkKNNcsXCIus81vJc0pRRmUE79uZkwyVD7axsPHyCiIlmzgYoC71ca5i3VUPcfJbGT4hfNezthNZ1xaxsu7kGiNdN+/m' .
        'Pw2XpRDj29ri+1j4g9GQiLci5mTWs62ud52pXczHF9/98N3dFwh2V4KqTRxbS7uFHCyz4rmR9KTpDt6hSgpBXBo6RiunDqlDZA61WMl3FxLTmISLC0gMjtoB' .
        'YmDeX52tiNA2MPNvGTLWZiHE5Tq0Br1uesxT47zgJ9Y310tmpxbYMhNN6hoSMmSIQdhg1vcXO+DVKPqJswlrCP0pVqEs44BCiO7UKu1DYOCTH304o2Gqg16p' .
        '2Uk8oLQaEHuBdXZrmGJKDQunCUecFnVgPfEE2TEnHtY3e9b5eliM48Y/6JJN6Qh4jd1QhAiaiL5TzSFYXrGWiaCBqLkROqBaHd5DuyCo6KpuM9qlsASlMNGI' .
        '40cdIwQjLVf8WfX+8cNpWPfuaoOYTFNz9wxT6/rmejHswctaat0SyjE5KcGs3AY8G75GK+dYoZFB7IFWV0FBxMVoOslnqfe/WkJYENXaBpwwLERVWrSfCKNT' .
        'RYW3gDMlrbKnWGt8f1FPCPaHKF3dWPCbOr9shWc5/vCicM5AWZi4JOjDkFMElQ28VD70gZdqB6v2sw2kFLPORQEbzIV79Udgkn1btT4MxQkbBH9jmJ/U6SQc' .
        'S0FlRLWohSGCumNRwhomBVFGjSIoPHhbamA8TvCBYljcXBTMWYAK/r5rEzAKp9VI7sDyqIJCb2CZmyURYFfaHN+bNW7HALPA51bB0d+b81haim69mo/YmMNX' .
        'yRT4AoeFCGW5uofaMIGg2RJJBA3kmissjK3Q0J7Jl74B/+58eL067cAOsIJMVQjuL0pkb1kquDVqXRAWDBBzYFbcNKd2ODu67WOKXcNkKHvL49xAVK9p3C5B' .
        'gKTzEWPhn9eqhdOlGYozuV4iJANHcfaWPHwEmvs0GgciC5SjWQHH3EAmTze36NGeTOAMLMFH4oB4wmY0+0XwvlmfQvxM0NJ/IrDHyvoEk+uZ5WiaZeEczlzL' .
        'K/TW1Yr3Csd/2y3TmA9QMFJjYlILf6EmHjNrOE8LULpuKHIcm7mHkTp3lcl/wzRNNydFYzCVf/zpH3+4hFRpHWuwhlBBR+ggi1Eivu4oo4gT/ggk3KZjK9Ck' .
        'Tr4l8cKaW4QC2OswR2QgDgILLTgJ7DPTQB5WvqtByQkuQeFiLI8MBIuRENiweALPwmQ3vBQ0Guda1oVd3OznGD84coWZug3c7QRyBd/gtO9lH7kC95SdKvjm' .
        'PP+t0HASOENSNeykgR7hcRKhO1rnnm+uEMsLYOWUYF4AKztSeaaAlSNBvABW1jdXSOwFsHI9YOXNxw1Y4ZnWiKEcEYj6D1AGZ700CII7cQF6cGLHwR8Z/TTT' .
        'zaXX/ogAKzA1rJyyAVeFKedOjjgak74x3F/iydWewcrAryjqsi9uTrzxRw9UaRXWrHG72R4Tq+FdjAwltEMQ/ySBw8p7rIGFtVzWNxcW/gWuMonlA8NVuDcJ' .
        'jdb6QW2ljXbKhvgPp7WevPPNFY/8FHCVhGVu1tzY2KvnLROJUCyV0SUy31wXbJ5Hq7CbJZR14ggxA2ts6pYxpaPBZ2GKUl2tyFKApPAQE7bRktzgo1nBJgvi' .
        'QOQtDaTWUkwX0QpP3mv/EEwNy9Jasqs9sZSTFvV4GHxE5t2yFZltW+Y+4NKxWqg+9nxzk1hOoVbg5XItVv10cOOLkW9EdksvpNbPCYuebbLsMvTwyMthJLLx' .
        'cvCizgrFlGDQDjJK0GLGkDr7YF8JpqbQYk9dJxpU0YxFk41kl50lsDsCE67D6nors1+9e4GzDDE8YzgL3v+jhLMgXGAyzpJMD8OxTO94CcdCnVqCJtYKv/vi' .
        'He4wTse17sPh6npWM7LSLDreJmlYsCeCziYo66V9vALt9OKPCWopCOi9mWoIRM1C5NtF9dRZsy7aQ0iEgzwiDjujdilQKVlG4gT1UKBbp/IglYt5Mz0f9VgH' .
        '8iSwupfaIjJ4EH5lktAl/ArZLzSjnPPAlUANp4IoYZ6jUiWAi3UpujGlr3kGSdEJhLN7y9BhU9s+S8zZiuzYKbytMiImTpkec1gSETwMzzJJ4BKeBVbRtD+z' .
        'gqanPOyVtf7DKyX2pJjSFhUR9AZerdtsD2YNvIbFUBsJNnISJ1zOAzpvogSsYVjHb/ZKVrJELj0M23KvzXEB2xKo526TDINBMRYViIswKK38wJUYexzB97Yl' .
        'voUejqAIlaXFoNkqFspUcMWqBFDXcESZpYQORpyBvefLHAw9AvgFQrwa/PJXaF2fAP7iie2S7JCn16LJBy9FaS3/BSlKi3fRYULLlluuF80RvCqM+xVAiHDO' .
        'GehlhH6Vld4F5EPqqCVaYvMpQS/icn1o0Etn3Vr72aMZnwW+hWh8dZqugLgsiV/uA205/f5PD20hOZ8eUoLCU/9xD8/ShhbegGdZ8rzcB89Ci/wh8SyevElK' .
        'VVicWZwFiIVZ/notiGXF2nIPEMvpd39yEAsOV3ewDU6CvrZBsTC6aJpxvRrGsqRieQwYy5F4zsJYvPAIaYmFme48w9FESeDWRHVR4Rw448qD4U1x8vHyaDwJ' .
        '/NSI90jO6JSQjSVXPaed5XNqJNpbf53cYYKH2ceuLNlV+j2wK0fSuIBdKSEYsV6sPY6KTSGTqBKztU56nrUx8WQhlDWr5PzJZlKLJcc9YcbmBSK4z0eUGKyM' .
        'RVUgMnNp6auIG736og10ZcWo8pjQldMqtAfTiNwLWqXrEJTR0ZBe1QwNlsYoJNmxlI8JfFg/H04yvFR7zBDDaFfIyXJlnU+u1iezI0p/XyrOkq/eBa6syFbu' .
        'A1w5p0S7wJXcmNbQEhULbPLuWn8TmyMwd4WiR8LcVU1aLYOoKAviXcEVtRjbD5kw/UG7IJw/yhhhMweTXYOZ2OKedpEra4KV+yBXJtncgFz5WKEHTwxZ0Tzi' .
        'JwxZgUfru7L7LVQIznOlfFZFyPuQrezI5wW7ckowL9iVHak8T+zKsSBesCvrmysk9oJduRq7Quk9Q+zK9rX/+rEr9sbPHbuyWfgX7Moklk8Nu7LzyE+DXfE9' .
        'Wucpey2KJX2vxa7sx5vPHrtyViwv2JVd7Mrr93efvf/qZaDR7kCjSTgvQ42GLD7RwUbK6OIeb7DRShwf33Cjxx/WsXrhT3rQ0S51y+MMOloJ6eMddqQkLjQa' .
        'Cyv+sGFHqzf/RAYeCcsLEw95XvNHGnh0793yUQ89EsqXuhXYo8w+MoE9xfyjOpWEPk6T+tHNQGJlTmxMTXhfsamLGUiV2XLxoT7gDKTJE7sB7PK444+CI3OD' .
        'YfKupH2pnT6uSPLKSUjFV3LiClzqMSYhnZfbk09DisL0oAeXNyjzPhNMCaaxNwxDYjw1EJr3GoY0rPTTD0Tyi0T0PBxjDEQKKRjx6UViGN8thr5hDFJBOEKH' .
        'STESDx6DdF5qH3AUkvzooJvg9lMCk2kAEotqavjvMQGJko6D9+MhE5B2xfWRTEGKzJBpvdIT9plPTkHqjkwp95+C1OgRdMtM3GcK0q4YP+QkpMxWCRs60uo0' .
        'FGmehNQJWhJ1u8f4owAvb8ZnPGT80Xl9e5oRSFmqvxav5UEtM49AguMW+7LCJSp19Tgk8sqQlG5sUCP8uXks0iUd+oCjkQpzF/oeiW8xSAvn2UiNyUA5MG+a' .
        'iERZNeaZVpbyHpORVsL6KwDvPM2ApDl/+oLbOYXbOSGjF+zOOeG84HdOSOZ5Ynj2hfGC41nfXCm1FyzP1VieIcFniOfZe/W/fkzP4q2fO65nRwFesD0r0XxY' .
        'fE/0vTGzrRmFTqMaLdeWc8tCirr0M44/vPI1ngLzgxOfDkYzXa+EIqyzuRxOwT3p1zfXB7HPHgB0UTQvIKCTIKBfvb4aBNQye8mz9UG2UV/m0C57Cc5+GtME' .
        '6GmKhvXQ/VEKsdK51Cpk9FbeF5iOOEaJfUxpcWiIH5u8MRU0EvSqbgZ2ulubbpeOWavlwU/lbAvx2OAFsEWxrK5PCuMy6CcleLGKXsD3U7R6wjPusgQr2VoU' .
        'yBBk5IiadJnZZ9XkfDRcBmGds5k+YTgtWQpHWp1MlhgixCqsKuGiKoG0sIPYmG2GbZNJRChDhI3Ihz3ueETZ4fP1CZmcBP9wK/hRJ2WAOhijs6X9cQjilfUy' .
        'YWNZnyjHN+unjcwOW5aSyh5HsTiFQB3RrUyq8qhF/TTYph3BECIxSehgp04zbIwGQTAHWvuFy9cMetZSJB9xX1+fffvj8gZsKOEWAlgKFYtv7cqLrbEviMBe' .
        '36S5Y7x92BToG5EvyvlCUpN4Sg5eioBRQ/6ItScp9nIoCxmXcA6KqWpOBr0p3zc9H90O8/XZl38Y7idz8opNl4HJigYwoXMejxKkUhu1o4CsEcbYgPDKklpw' .
        'KG0UUKOzFfxGdyai6UI4iWqRIwBAizck2qpq3OmXCTnL4utjUYYQkVkQGIAsWkdUH+3B5uuzQjvGAWG3RM1+wHntB8v/k3lK4YPc/9FPdlIze5E4qE0hP3oh' .
        '8RZx0JJI+qoJ144YEvZkivUpfZDrFxbhvVrpInrGDBZTEtiTi+/DLpVSZNGtESV/z5duLPCY2k7XZwWwg2mB7XVWecK3e9vH7C+t29IynI0+uP2wlFMYTB9f' .
        'iyyOcZ6ck10zTxtN4Egr+arqRt4bZ6gdCj70ZA3TkcUP1vHXo0VgZrxxijUiYMyOYH0imbry5ubRNtCe2BJDaN1AXaJbDSzI1GC0ZtWXwa3WUpqO5mr0URBW' .
        's+GOnSPBjgiFYIgVwsJ6oRwR0LzBbxDJ/x6UtoIN1lLxnPMJPqXYx9AK6ZS1SrMjWb9xtS1uTojqDAjoqWztvFMWtjYQMHrK1LLtN7G6V5a66riVnLG33dPU' .
        'Pg4eKFeZ7JCmBVZ7k8OYaQoxYFk3SfLKqWfqfRa4qgfjV4p9rB2X1Bu2xVz8StdZpMw5xeZe+TTgNDiPR6M/Tq/mFgRNBqFK5IVYeSgIVejwKvUZXsRa5KfL' .
        'kz7bDfAgYrtyNKRP7ES9pHWGLsjUKwVrEvWkSWECjq0EQzNuOyMJbMfAAkzuqoOW8jEdVRNk5bzt1A+sbsDYAw8pxd8UTsd1EyGkRoZw0lwfJdZU6lSyilH4' .
        'MXR6zHxzm7B2i6CIXOUIXrxGCkxfNq3CCKedmCLhRvNWX8KL6LHdCd7cRkI5FW9Ed57HSBuYJpNpF0SCRA4y3G5jqXwrsEsKk+gIIwh7GcgOLVUxX9IMkk+3' .
        'mPV5Pds4XjibxZxvTlnuq+FB3uUgRIsSpjgaXVX8Rn4JPfGzlCHWzg4nHLqpU6mMDopUpvG9HHFoTrUj34PyL3luRlEfWv1q0XE2ipBYyfjQ8xquWRCQcXiv' .
        '/tnmfBnsDowUuCJ9fXObVHbgPySViDQrdbHDU6LrrzU0wqnHKHsinVVmQr+pfGeORGTBSlLZWjhIthPt0pNLpRuBiitjDhh372zn+8Bi1W2hK2H7wof0UzOB' .
        '7u4s1UXzF+GxMB2xsvYZJjaOrrL55iqJnUEAEThBIK73iy9LKQ8Mc2Q8Y8WZxqTMlrwqk2+rmFtJx29j5WVYpOJXAiGe1ZJSE3TYuRG29jKnEphU3LiliUxW' .
        'pElbpiPEd68TTwu+1C8J+ERY8D2Epmh9c5XkzoN+EAA66eZZKlvnQa3Kpk0jWnfnWSSeX6gs4mqSKjKlrgONWk/D26wctW1JUW8OFPm3Rt7UMQOkjhnexnyJ' .
        'lOEikPJmZI6sFuE4aN06HbArqtHywr2tnGi0vblNn3bsN4mtquXZM4GHVvrjg/B/ZfK8FgY6nR69FA/b+5Vqwb+E12n0XwT1WcG8OmvIIH5wTB8ldFwByQG+' .
        'lFEUVxLQ+rxChzE878MtpbXgiF/NIOTYJh64+eZWPdmTSW9cJ9ndTEcaVgILnW1D4P3b0TSk6LKz6Apbxw0lh62B2DYhW2bcYPOlogvDE5VJvNZ00gwOEoi1' .
        'tdwozzemuUeqTMGkUeGM5tc20vhpHCxtLNX84PnmrIiuh/OkDutsfV250p9VCJtzNm0RS+KMczYm9iHojDKJ5IK6BBwk263YRu1SiRGtPqrGo93Ns6iqfhBb' .
        'DY0EtTr2sLGVx6RryIvGac6Kb4CUWP9U0AFcH3hRut8XN6eznpehO4+gLYWQRwWbRxoLxcg7so9uoSoV4rOdlQkiVvubOARQ/1bjZC+nKY1C8CoN3kJbcmth' .
        'oDzgWzUJ2wweltgLM0FpT3x4UVZUnXMwno9Bb+CdI9ql4Tb5qq/InWQkVlgJ6oedlTXXQG+orG+u1JsTiJ0Md98b925hrU0jSR4NxS6JebIMROUgOdnKeI9m' .
        'xO2hVdJ1qVPNPjYtp3giiixFwmBMnRbW0ZX4PRGLpC1cnZFNY855jgXpFMCREHWBV8RMsuYVKKGBL55vrpTCPiKFxyCpzIPuZmd4xwxP1NBVOIGg0orZiW64' .
        'XdhpPG7WnIPY+YoPbzEbqy7FEsbsM+kdVF+v9qahGg7AaP0HOJRqCa1o3p7wI7b76J/pQTPjCumhX2pcloub6wSxL4eeUyAdIh8fERXH880hp6b5o+yO7Gq0' .
        'RkGEkOWo34K2wLoR4QUXv8WIY58x+CqrXwqZks/WQDVSI0EI3uTETpJetdoC0W6Z7WBpOv35xJEnuXihjHPZP6PwPJiQaeDpfHOlsK4A5EAedJSMHjYzIavp' .
        'fEl3SrzMlSSh/8rhL7EHKyLkErKh8xDzOYsGU3DVek9gl42QlI1ewbZDc1q1zZ0RkfHvIeRsRELbzHAr27RI/t6RW5L6e2TUnNWOEdvMlEGO65tTUno46KYj' .
        'HOeMb81RQFLKTSyDLWkYajAKv+sxF9CiXAWNtDjn4N1PFNFByHXbjFQQS1LhtUBbLMybbq559fuBbnIjg4b577AjuYyuFxaeLVhMbK0MN70/jGlnsmequ4kt' .
        'JegvGOYkwwYzGW8OdRI8a1nfnHnvh8JuKt4JbzVlONUNQmC94AWwKCwTmqeF3NgL/VJ/gyjYGZoSM6fy3vjWLuD/1RfD5BQ67eqz9SSlz7q+uUIJrgPeiGZ7' .
        'M9nwoqJWh6VTS7s8PFNVSsTY6dFpFzJDe4U15jBm63phYLau6zRqUdIbo9UfF6wREqYYa6/tC2wVLnIEz7sjBGboy9ySY9k6IcJW5HDOkFzV9oXp+oRYPjCp' .
        'DvZqJtpQq1Cwh9nKboEtNXV0Js83Vz72U4Bs4DKlKYHtXOO6Ksty5DiYboHpfHN9IHkeXIP4ICTDRLDZ3Nr5CIkoFmyz93Og/KMfx0vqjBEVw8Beo2AEInGw' .
        'Sxc2vmjvKKc6q2MTnTe27FDIoLGZBVAiLDQdhCXNbeB5zhNUKQd6ZBFG+7odvtktr24WyylgDceZO5thkrU9WgHrjcQKm+nD+8KqBKnrscHuJHOJtean2SbY' .
        'nVEfdEW3PIVl/TWhp1H1hXMceiGtQpzzyNiHxNRq/IEnCiyQKY9J5PIYN9PiZiOdz3TS5XOf/6RieL7zn/j+H+X8JyO/KXOM8OqBY6DmV/0kxkAJc0P2gyz8' .
        'AYNK5hf/KxkDtcuF85AxULOEPo0xUEqFMzDoD5r/NL/6Rz3/SVhv+PfrbPgeNv/pfrvik57/JEw48Ev8Emf3oPlPFOKHn/9EN0GRpjMPzsKszkxrTzr/afXu' .
        'H9H8J9ZGk9qCif5mHgC1R3/z/28WlLpfH3oW1LVMOIsBUcqEc810qEhKBpyHK7N/+5SoM5J5+ilR2IHqd+zT3uwOjJoYcG4YGJVS4yAdAz7cPDDqs6O5v088' .
        'MOoSz808O2riublmdhR+ODGakVjq9tlRZ8Tw5LOjhMJG9HqmsFmMjhoUNjdMjsqIK5n7Vt6fB0+OOhbOB5scJeQ0dRzCx+Q08wypiZHmhhlSmZK0k6LfPkPq' .
        'WC4fbIaUENFoWDMT0ezOkJo5aW6bIVVqpuuoX/eIM6TOKNNTz5AShhqjSFww1MwzpCZemhtmSNVM4pWJjermGVJnlehpZkgJ74yGWjPtzDxCamKduc/cKOiQ' .
        '6Jmy3t1jbtQsj+uxKgRMIohZdfGxWey4Oiq8dJsm/SDN8uL4BVI5DoYtnuva5eeE7WGTrKrF4H/E5OnZFklDqccOeQSsfVK4ZZr2tz3lGClLR17GtNxThfb5' .
        'jGS1j3gmCVbQXpMYfRi89oR65dGMEA3i4QUEqo51i0RKTO2xCiEjcKpseI1IRM7jQMXj4DDWkd+ebq4Qzwm6nk9EnUqCnLPnbp0NUmQ8Vka9vFFd6uBkIT+3' .
        'OhPz9TVS2ge4hBBy2hwKnOvqVImicFOpnDxD2C0JZ8sSsss7EfZmHA1+ypM3WiItk5UwRq2yYXewM0G1BjAKx5UdiAW/Vim5JTI10kHrgzKSpf1srUEEftDF' .
        'sk7l6eYKuexDPRIZBC0pjQ2fbE8lQb9IKId1ZeJeC6MsVdeyUivEM8UgZzAOxcqawTM97IZk/DFpD3NsijQVlITstMgEj55fOAlxWhFZvhj9HhFXssvDolQE' .
        'E8UqRYENY7D6Cq6Zby4L5gRRjyOyyVpZaxssaAnb1QqzUSqrM1rc2FLosVfDi3vzukkB560vI1JjFPbDRISe9ZUu8pZ5k/AU3RgxkGWtrM0WCdEqEahtQY8d' .
        'SWnYBlwYwZiAr5ZIj1CbI8maUivNN1cI6gr4C1tJ8KjaGCop2aZZvzq6IHMSVnTKEoGw+TIJIay5hVD25lTuUUDOwZDPPPCbbj9vTYLYAGMiaRISKLPcjYam' .
        'dt1hmT3JBG0NKRgss3jL/URCc5oi/+brPXk8HOgSOlMxG3YZn7AMryRpomTPlX3pt6A9yGWbPeGeCwVZo2oEWmITsYNr1si8vrn0zvdCuLD/rYbxGLFWHaON' .
        '0FzLYzBuBACWW14XL8b+6YkZXpaR4OPkBpNOgO5bbh2GorNHZc2ucvzhidd/INAF53vogzqaPoP59J2ITvXpa4qatbqeVaezdNrXxFQCJcluTkfriYHo2m9U' .
        'LhTtab/04QWFuA7tImo9eCda6ZqHKkZJhhClHTfTYmEHNbiwX1t43a3BTmo9UgqVmoymo7zWf8qcTk1SfBbRlFg6kQRhSWNd4RC5PCJ2Dc6UjNn33Hzacg4f' .
        'fbgjnw8Me+HmDVZb8SFHNj4av0YVYJm64fPNFY/8FJCX0KWpftUrhLiYGZVsJRHS+FhvxXxzXfx5HvUCD5Yp5JIXi5norxr2vhASbzgXUpIYiRwp1DSr3BlH' .
        'W7NysSnenq6AJVXhLtfRF8gZBlalc3PDOKtAcky1jmtSIfmFB0UfuIYNhSV2Nj1KLZAU+L/TNIr55ibpnAK/cCQOZwosnQuc4sVqazhGiP2PZyTF7j1r605T' .
        'nxlhac0y8XGMEAnEQtchqeGFyOQfkRRJ/Ih2XLVIhpwFKtitRhKItlZcSG2NcJHtzUYu77969/X7a8EvcII5vSIaibwbk8kz26yyLb0zkxTY6RONQYZt3trm' .
        'mK29FRbHaNgL/qh+lmvVdnMc2Na57Jh0t0p3745w8HXFrgbyuQ9YvG4eF1OzFrAWBIfd6+r6hBAuQ18yj5ZsaSriayysaV7ohUQFyRxpozzIs+/M/jD1bbVw' .
        'l5YsOuIQZiHxeyXd613DJvZaq25kzpobQwwoATkuMuIGkrIve0MjdlAyCBa2SHfGV9XZSqrJyelyVwin8C+5xVbH4BsylBx1wLrEfMiG4F21btu8mOdKlLK2' .
        '60qP2kOngLYDoNhLZ4VZJgLVGwzNCD482UisKRCnFTXA8vMJ6sJsomhf9mQLNZZ5NiHo9pivz8jkuHpAfEG2ni++SD3uCp72yA3iSSwpqU3wbGktoyu2DrIU' .
        'Y5LaEwkZY9tB25lgDD1LDX22p4SFtMEp2+F9dKtTED9Q60BVzzdnBPIwAE0pQWasTbZVch6RudUtWz6pBAxT4Nqgw4RBbXmbydRBCsZgRTbZ4b+wX3rNstRI' .
        'zqyYOaI2ZesxNa4mOuVone8xRVJs19VENp4zzM3ID3dsVGbh1qtIbhYCxvL65ow8j+E2pTFgs1xDYin3SDRsSdP8np9m43htlu2r98VLZAOQePYqDnRKdobH' .
        '6Ow32aJTGtPCWtOM0XaRTz2NFqdMpl2C7JZVK+xv5kGTbTM/0lzslqzQXbPc080ZmRxDTEjxXY0CDJY225y3QEDWQMuRi0CPI/JNmIXI3C2atKQ7P9wpjmfQ' .
        'Gn9gMn3L6hBlut0sSdGTRFCyJfkIQpH1IbJFBoIs+6gLGQ79gELSYW9DFo1I9sXVI+20PYkJM7jWOFlEUghKSs3GKoZAdlx9GPj5dbgpLE1pMRjqW6zW78hW' .
        'EfQgcceoHPy6AfErYX5aIiVVj1a+C6Ior+UanK5pBNps4GJJVRG8sNg8Q9ezOo4/3BXYGQDOszXXjwPM4SAoUq1rvjzTa9cGTHKiai8mW8dH4x6rfWr8yKgx' .
        'WCW9de0wVWilqWjlXhnQpjMbiZOz8kBN5hvCpePkHhqrOUVP8LPgaikeDu/yxp0ifuiAIM83J/y+WzhnoBkEHbulw5lYTjTKdnj/gwi6M9HWjAK72EBM1thG' .
        '43dpbnASpNAGnUlYDGN1LZgDx4Gc2pzEnh3JziUmMwqbgpZNAqwrjGYZH8mB2UdbPoLKMkbEzDe3CGW3D18Tm5pRdMQeyRZgvtdwh5AO85LqtqZkjD1BehWT' .
        'db07m11GEEWdGHBwumlog4OpDZNNqVmW3402bJJYsa3t0OvieExwWplIXW/Ywu5ADVIq7D8EqKZtvtm3xdezyAQ23Ro1eKoF4XSxvtdCnONkVeQMdaQ31rby' .
        'yry5tjJ7IVzQspiQ4GjaBp6tlQZYKbHSnJfJjIaNZlvsunQO2XoDA6eeWyDplluSj2J9SHMUwtLYCcjfNh8expMEak2ecvzhLXLb45nBQUGd0Gx44IhR601N' .
        'pENZl+cIwB4p8M76VdQ2OVJu6OFMzkR5TlfHeG5ynlnoHYgVHSSWYdBcsNlcZwonJpDI8rEcYwPHhmGKQRIRpeYy5mBW1svNzs03V0jkHI8Mm65Z7pcEALNw' .
        'ak+gRgRTuLVAoqtpCZ21IuYYO4yThieVeoicC+etlsJDTa0FCSoU5hByGQ01Pg4XMUQOFnOajWCNiUxRC3McMvb8eEYc+67CAGZLQEjrctncXCGe82QxTIY4' .
        '82uzIMq1k9GVbMAxQhCPhmlFaQ3clusKe3dsXhFl6Q144gcuMDNpYeh+QnyMHm00MgUmYzTmyOzWZ7PWquzQBNoiUu8EkliNC7addVWraU43t+jOjl3OVYav' .
        'iF3AFvA2A5StlINfqgU5pddqlAh73uLgah5dz+TJtS4FxGBhZ4LNVJxDSBJGbioSpK3pAwKr1YiVwHoO+zXLYpPRw0eEofQ6tTpSCw20ZpZ5Mml9c5sW7YgK' .
        '9sWP9S6SKJZVSikmJjJXDh/iQK/DbUn0bCOZmOwyf4/ktIOooBN+tB0LLKyORr3tswF4sNFHLx8h3pbfyIkspn4FFIUgCaE1IGQj72c0KAAnEg3EwnxzRjrX' .
        'I3PgOXOfSUyHgweBctEWeDY46cB3krVrOizxGFJXN8rIV60Kq3usRB3sqDc8Bed6KgudI4la2pDMtTahEBuZvzYk3r0xO8Z6tC2THHMliu0ek17VM6okcTN+' .
        'frbuhuGULG5OpUcvg3EerEP0ktzAxbGsbpVqaoZC5KTLMBwFJcqhWkjCqikLjloauQ0uALkCVlABGGr6W5phj1lG8a74ZGAv2jRqYr65IJ1LlDIfsRI1+K2s' .
        'Dy7pVZ5WiU6AcThMyQ8ELMJxOdiiUBNolYT+hwEuarSkc5QZEqpFsRUbsACj1JrNE27eFtuo5AORDxr+Jxmo7ec+CINbQ6+yRKyT6SE7AIkT+koiHZFhUf8J' .
        'qu6JBtSmzfnmKoHsok0CR6szrFt23rcoA1jmI13S5Ti+LemExy5t6JaMIhfdSmXURiNOf5t8SF7ktG1kDpJcjINypaonT8oV80jhj5K715r7yAHFeWV5klTm' .
        'uNtixynr2RJusDduch8XN9eIZ1860Xl2BeoEnkZvXFssobrE1U5LKt2FEh/LEZwlXxD1TcgH43TH0dSoVLJQBmt8kn0YlEps6tZuikTCyqBdrDEPsDHObiig' .
        'K5M15sM44laURpclfsmeiZWGk5kHwm1xc5U4LiNuIhbG05OyQmQXfthX0gHFGon050bxE2ffUIxmpnYoBq3I1EVRqgaXSP2mJH6/cvsEDkotZln4ZzdURwQ4' .
        'WguizC+bgz9FfZBFvUsbj5skhoUMTBa1sNiRmbyhxOVonyRZqTZMHTsf7ovywWCdyEJKseFankRbzZxlBNBhkCG6QHQbs9NXF8M79hHjlEMz3VUSxoL3sr5V' .
        'YpVZrCkKiuuNfU9aPJhvLr/2vfA6CdaIlLCrIV2cqlLHEF1EX0Xrc1A4YXWqN9GxJHa/VHvV1Hni9xlVocW3LFNFjJ0CzjphHqvrk2//QLgOQsLIFtcVESgC' .
        'XWb9pnyY4RrJvmY1vsLOJnGIb8BEkECViaCodWHP5o1D9gttj2R8dcYGDwlVPeLG1UUVuA6hIwqdNMNIukxDMLD6aiy2gbM3x+hrJnAtSZfTGH3D+HHrtWER' .
        'izWHR+IiNdgRZKjmccrgywueEICsJAmlCdfVCnZeEJYawIekTFKR0E4BhAocuRnXN7uS+cTnQO2/xMc8BepkkHoesFMSlH7wBkR6c3qUeuJwjHGPDYI2DoGx' .
        'x8azQ7g4eJtDZnFBs9KSM9agnhMtjB5dyqnbsfcxGHYVmhusJb7SbLFuvyTKRRAkiA0NkQmho+4uPblAnyz3dYZ258MbhXcKz9NI32KAg+om3siUOfld+8b3' .
        'Bcl28THus/TBLsP08jRaK1gSl/JrBgupDGGMy5G/pgNcEMN0pqrXpJbEugbLc0QSh8dp/qihk+FYwSgopnu+2YjmzdsvIZfPX/hslqJ4vpw2QwbPhNdm/brP' .
        'jNtm/fIv/Db7/DZrKT07jpv16z8znpv775AXrptdQT5Xvpuj93/hvNG6x42cN7OL9sJ7c86BfeG+Wd2cMutPxX+Tx2Tej57/5oIoXjhw9gX0woNzWjYvXDgX' .
        'uXAuKNWz5MO5qEzPkBNnLZO/Pl6ckkij3tyaeOFhRCaLhOYLI84+I86xiC4hcT4dhXp8ZpxTCvXCjnNaNi8MOaeF88KSs8eSc0pYz5kpZ5LJw9lyEkt0W7ac' .
        '4LJkTolRrEeoJsce8ziHqTfRypAlQ7JNCysYmbebBqxio7puXjlOPqIHDT8631wjj/sx6TQOZipjTBYO9nBY+8vELBq4jYqvEdX1pDI6AG/dagUhJ05od3rk' .
        'ERI3KCBi4TidzQCt4w/PyOOh1Do8diaIolD+KEKERCjdIlCcK+R4abcMz2KhtBuIkpwf1QI+KBbDhRVRB31oNmvZyLDp5go1eOHP2ZfRB+bQwdZljUKxLJHZ' .
        'DbdufcQxy1N1dNBNN1c+/lMgdGKWuWFN0wMpckCaZtAaznw7FhY318et56E5nsiatvGpY4rWGRp0oKuNM/uUuXQuS+gU/oYNUtEcmdTYE6vJLOImDd75ifLo' .
        'vHn9l//1Hrvxi/dfXgu9qYgU4KIq1oi1sbnTYExcJI6mGPkNee/0VeCXWPjUGZz1Ta8DApLRSR/YNWwpEFbgtFrvOGE2rM1id+zFXVBkqPPNCFnStInBHZHw' .
        'ZZG3D2zNkp5b+TKsnaR8l+Yh0Ulwky6Pm7PSu4zW6fBlxuukHAbUkkmsOg1YdgZoI7C8bQGYcNCc5QHgQCabpMqSpJN3YRDfLPXlih4nLjLNtu52g7Pam6bB' .
        'YeNdEud8YQCIAiZR8HrTJYelH4k4/LO0+JT1zRkRnQLzeEfgrUYHmWwCFhjlMkA0MRC8qHPnmvA7bAbMIuiwdgK2lpVjgpUK10mj0oxgViN3L8UhEVHXwCFI' .
        '05O28VZOhQvrYeic2hms0cdz5/U8WmfhsNeBDJ1vLkpjp5sYB1bsBr3JUjKUJ9rdaydkRCdqjNjLwSrPUJg8eB6yYy9jOCUQRJn4LQXFteLJnLTudYRwuE6a' .
        'ZeXe4jRTo2lMjFY1RzDfXBTEw3A/UPfMwm9ZrnqunKiXtWuF3ZhtjiQlaIXsDFWJGAxr7Yz0gKOfNb/W2fi+MVS1jbm/pyvd0QJgknia/e6IzDgYe9QUNMuC' .
        'NXPG4ckOyxIGlQ9ZWXq2iHlcX5TiMS6IYF9pLZPdhdWw6ldiyUn1FfEAUaDr3jLm7JpZInEgo1VA8piC3iLbdLd5RhlAq6mg4AeJTSD7iopUxkpmBf9lZumG' .
        'c9EaeYOsYEmAHad3qLkOTBlZL/d8c1EYx+AXz/xVN2wQnsRZn3fCoW17JzbGwCmthZHoTlpLMRugrazI/jZjUqMDueUu0tSpGotsPSVsKYajr54PTveoB13B' .
        'qVkQlq140ThBkmkKQzUjmM2DNskVHBpWVFvcPOo225MfCW/CmvSxeGJfBNEDK245idhYXNWEEKkHR5MwlcpcG5JW6MBUaQzeahJi4TSON5YnzWPyg1sD7gLt' .
        'nQCqIssdfm4yle9ifYrpI920tfMYVA5AV7MAdzc3Z4R3BjD07Oz144CHvK9kMDZsYcutj/LHNGIWASrXVPEX0LxtJhDbp5axFUOxH2W07qfk4OQATNYOXkwY' .
        'FW7a+tHAQaYBJTB0pTeYLCtoNs61ThYFw1izXKvuELxN+PqDvGy6Oesk3oAXEp0OBsDmQZFm9InmxqBthC2t86fZSSiyTiOlPHraIFWqoCqbMK41xfNyE2gs' .
        '40lb0iwXT9KgTRq7Ml7Tlm2WObbcHS2y78nPfQBaLQ5MRlpWH1uR8aEOcK5JmB2122K+uV2MO6XZEKpAf+WAbZVslvI9hQklQ32T/EPPoCToGW3R65wTr+3S' .
        '0gqz9Tjxbt443CIjl239RMhDg3VxJwuASWeNfdxW8kIM08lJs6pjJzyfJKZENzMpKwZ5K8Mr08fFzTnLfzW+KDD57vs6c47Ql/gI2aa+m0XK8HCD+r6pyrh7' .
        'rVXQm2laBmBngcJLMlOQ1aw+l0DzBq0PiwRDELZ1ALLnFI3iuOfY676qi7BizhSAlhdISEZnT1NqiIMGbHlxc7uIdnBHTIcyMNPokzhiRWKyTqqRbq7sZatl' .
        'tWkyOfdMXjxFl1hZJeDq423hFIY+7Lqfar2+ppFR6czxySVEwDyMZopxMJBuqqXZQiT8AVb3dKcVxDLJ4Hss00gPsF/fXC2kM9gjrIUT2sa2kIDikcRbggFq' .
        'Bm4lU5xTa5w9jyivLd2ZHYnVLD/8gLXnjkVl55OKKKa0NVF08rol91gZmmQyzKFj+WVznECAWFiinE3LlAcDJyIBgeoIF7EQTcsIcOqy19dY3FwtwLMApVD0' .
        'xRUew3OgqhuIDdYXSVtd5iLN69ouXwmJ1qJdFvikZntZ2d4gwCK7y9XXgDkLo+ENW6mMHk7S03q/EhMkwkY8fa5UaByTHpwEdDPhtW38jbEaP6FwM0z0WPPN' .
        '7Uq3O5VA0l/K/OWkrUeAkETEWJ93ESijigzG10651Ig5V1vfEAocgQkC3bPtAdA8JaoF8hiPTskQaJxULjBfVsMhQKx62o8FPwW70okFKMNnUf4CHFfNUoII' .
        'M4PY+bC+uY+u7VWhasgad2FFm3ndtfKt1CPqrDyJg0v622xNXfC47bRMUr7PijVkp68e850QUnW5C4krtRMxClpV2yfYYq1+qOcqMbWjfxu+H7tE+7IShQ3B' .
        'plCLKiAdtqxZb950c1EkV8OXokMI4hTsRo5MrFBQwooYLSbDm7BPdM3oQeS2ZWFLIOm6xDs5s/lQnjZVqTiq4BgbbckXhJC1WQW8BdUB2J4+kHPs/CyRBJaC' .
        'NmlsBIXmD0COyBYSZfxg9CtQWrpVdX1zPht8Ebd0X8XJRIZEfbDsjWSVGGWtV8E7THkL3uF+sYoDvpZ19nVbBiebGa2OTA0JzGlYjlmi1ISDIJIDdinp1gXw' .
        'rIyHOVWZebbchjsfXiW0C0imj1i3amDXRDJgYpOymV93OqcmhMMaLiB2pd7BAV5Yx50Pb9C1fUgT6S+JujWebLx0UfcRp9yYxgJPm1o2aY629BUSTEh2uFQ/' .
        'kOm5E0ukREVO0LwDjdFHCoP9tOLik480bhsRyLoZB9xHrLrkuyYZwOtMUfB5YuNZ9LVFKg4hExyxvLq+QUC72J3IBpM6miBIdFIHtg8H84C3yxCdLaSmpUGS' .
        'XytCcT39i+zNxU8qnQyZf5Vqko3gCmCDoWmGC0uu0GXdhIShsR9pFqACLCKLj8VPjYiQw2g8J5owS5i5lDj+mrSHLbv1dz68XpK7goR3KBxVGtjnxE7jNFkj' .
        'q66w/mn1WfybJWc4pMBgUuR06fND2fYNUMUJm2vqCRcqRttLHHmkICbGwX4ghLxEjfyrtY1eoECKJz7dYN6AM1btNEU0wihijjS1s4l8YyxNLlZm58MbpHcZ' .
        'FZULjhoCylbEGJ7xtzVSEDwYrU2X6EFrhCV8sNvZwew+AeDynkTDq+uPsDAYo3lNNJYqzko0VBotm5Zq9ewlMtBHxHcm7M42in56prZE8hYcMYuN0V1liiWu' .
        'UA8IPAOPks3NObk9GDnFpB5bxlbs4jhFWOm0cLoL2E7TVJ1o5luQQh5HNQxesfbm2hGWQVUn3KMG+s2xT1pcvpxrhaiwMRZ7cefDa6VyL/xUhavAvbEqUyLW' .
        'EcJ0BS91IYnXCDpzDIy7BTfENgwEEt56olqWDqz1vmKCKWPn6UmKPY0oYz2xa+fDC2J5IIwKz1cYfqTlmZxIRW9N65EJpUHf3AqP+3QL4RVRyqkR2Fjy9AXs' .
        'gWYjVh3fKmdrrwK/WZGClV4JPJvD8rL/4ZXKcyUnEndIsL7vWoo30ElnalSTJTSuXk8xws02FDQRanbEwcgg2oLOIBNiFPgQ2BmuhQ4nQE2tEpGO1shEqpW5' .
        '4ZY6rEWdHF7jDvNMUVh7TZS41bKRnXy8lhafb86I6sOCr2gDmEOMbSmlSHIcY5hPieTNWtucr294gSeAX0UZ8NAHKLI6ukFaZWBsiehKmRXmm9ti77MYLNIR' .
        'ELNkoDpHSVV19xkri7cFS8/OTjG9jaPUdKKzNA9btkeaIJWjJSAG32Z7AkdAab9B52AMJR4vpIBUdkXEi0YCFx2ZIEeah2QmhHe31YrCaJBsWrQ1YA+R36VP' .
        'O3f3w3uJ7AQoK8qApGLVd/i3ZH+Wnc2cuBEq7IuvNBYi1PF3Au5/pQl3N3hby6D/odAsbA5SEfDqnGY3Wv4g9hJYHR4+tXjO7DjN8+weicxcDxPulEgAxzac' .
        'sL7ZEka9efPul2+//PrtteAs5iHTqB8uqD8Yxem4CaIHZVwnaejVq87O8jFFmLDEEfU8PRY1JBERe+nU7pBxXTslhHA9qG9rrBhkghW2LosgJf8mtg4GNhEy' .
        'J6sg4af6EtP1mfe/ggwpZ7aRammvwoWxBqTAEUMKnfVCdWtwMrYPz0kGcdoZP1XNqRRTokzon5Kj0yPS5AqT+Up2yEaDqjEODgd1v7rUM43zqpPQ33r24K1w' .
        'NsTUOSW2viRSRy1o8U98eFI2p3BVhI5H4yfBezIfsh0Cs2KEGWAulpiNcpSDPGRPYHWLxqse7rem6BYsMMOyx4lpiRhm4xjxTYidFzjjXiMZanQPtkjPPIal' .
        'qhUWYM2zna8vSGCHDuhWUpwhggeS4pg44PoY0R2BQJm2Zopn5VuaJ+7EL1WQHFE0a9M3S2OQlFJlA0+XF4TxQB6lTNrhMWeJbf7G/N6ntmcOGBu5mNbGLCHE' .
        '4lWHAHPwrLHAODs9C31xJUIjvliHwfQwJtsREi9fiCiQw40HWyFpYEYWsRChYIa+Sr5fSdym6wti2SFOKkKArmDnyFpPXRsFghsVFEruYGUtx2InS7Rwfovu' .
        'dCIFlNApkDBB9Jj2RodEQnTWVsV+LwNBB1L1hZWaYaP5GjhAZzkhkUQd0ju4+EnipQaMFQcdDmR9jvn6gjB22IEIMjJ2DbZRZcMeldIGrQSOKitUevLpmmfr' .
        'iN0SlwRRuWV/K8FvSj7Can9QTN7IlWSJmlVp6Hg4bb3w4vkaOZPPokb6N3qinVB4vOPITc2zTNePuCH2BNOp5NYR03syxDtPmS1AMJPLRAtkkflv2UWQULO6' .
        'HOKmejwoMjmDYhT2/2peshJDIMlK6XOf9cTkRpfFG7EiIWqk21s8iOd2SSMV32uyY5AthNUo+Obrk/I7x5b0mAaWFCJ2+maBbK8NbBhTynYNbKRPxkFf8eks' .
        '6yPRJhU8XR+tijSJo+8xWM8pnEqfx+gfN/hauE38ctSfnMvNG+/nXOcnq6Iap5yyzadKfUzy8o49hJvqCfY6xxKM8YpsDCdvgoit+p4sgK04q4lNaKvrM07b' .
        'DXCnBrNPyg2D3JQj6ogQpECk6Br6y4q2bQT+bfHeKY3iCqyDsUl1kqAs/qSSASn5uSx/KKOelgbAlwOx1FzJxDbPkkZafg9iLm8+fQ8lZGMg47SvEsNEQDlu' .
        'bhXVHlcSrGC0c4gs/UbcEpJrbaJzIYOAYtSyH3McM+Gu1jZWh4HiJDYbX0vaCPWCpbL9SivnlpyXNFs0CGMRitnV+ZQJXJyGfZNGesyyJGbOhXGWTzenrfX1' .
        'bEipC5mI1ei7kRYEcUf00k0D/5h7KYNsQ9CSRrHB+E78jywUhmur3EofoThBgGI9K7es/h1CT8S6s78ys6NJV5+c5sXpM3Qm9f3oykDA0dzRza3C2MElQbsF' .
        's6idExwYrQZGjmvtI2Yjv0H8HJuYtNkw4KmdMY8E48CiqdHpPD07A561NDrqaul18HY3G04fmcFo5Kgoc8hLNLjzgyLPERAeRsBOZr9BWjXfXCmIM9gjTo4J' .
        'OuIqSjyrTfpknkzWUtXiaDwjAbmNE2PdVc8OogxGy1Em06Nsbg7FCpuJqo1zopr6onk03mRSRytCqJIhnKDQFdkRXOhq5p9kOuzfskak1KYtNN9cKZLz88bY' .
        'A5SMgVG4TgxqhijNLMWCvSKQYPtgxrVYyEtnLw6+d7p4dTB6Ge9lT3HFCqn12Ezru2Fc5jSCZgPPiPvgwbWkhkeE2StTUfZlXsaXLQn7PKMpP2ZCzje3qs8e' .
        'l0+zUXmLr0tQDzuascvI06WYlhBtBiMnExt+mUs+TUnlia7VGTZM2qUXfLKkBoSbmWJi4cVoNRjripkiX6mXgmhYyAanbh28Yp4YemYjl0VSfKuw+OXV9e1a' .
        'tMfnI0NzDmnJG08b2HQmFLZTGvwKnKerDRupxDL6/qqufyJvua5doC9ss2HJvZUGtWDckmd1tpFmo0mEdyBbGN9Nrrm66qrxnQhJcyU9QXvBhsh5lrCm3sL5' .
        '5oJ0rmc84phV+Oyy/Q2ho6AozlnbENRFz4hOqYpJZz3wjNXmbwivnw2K8GRItG6ITNyHtzbFYgQd7OI1p4XzAG0YMowWf7xOqTz9d3wy0FjYXUzQGf8k4m6Z' .
        'jxPXN+eykpfJjR6oNerNGLshsWWKgVXEtBqDXica1tyM1ptbZwzZjuy2V/gCh42Q6H6QJqkTHgLR9eoR5Bg52VTzhRUhw8DLLG6uEMglKqOPTFPYTCOGOC4I' .
        'q6NHGFUGU3LnYNEyckIc+jpA+/PN1ZpyirWokXpHj5nUCTHXI6tOkOgYYzBMDZ80HUUyha1yuYx1tVUVqgMVUHWsUG8ChswV0H+PHKKlqFgpYIgoKg9kHoZj' .
        'qrqsS+yUsZX8EAqQI1mB25G+czW++PnmavmcYC7ibFgb1pernBRWf8nmyMQmY1cUX8vqh84Nd9jmo22qEy+ti8jEdDSUdJp676S/TvcCmxxsQHRuxhrdSFOT' .
        'SLm/QEow4c/xU8koQxwr4uplVQWLWZ5iurlWFCd4imojotSwQhPDTspkNpEoGPamWkt+LIKS1/cg6aC6OY0NKQZ5ruoywsMLhirzxOLBDKxzO2XqpYyJAYXC' .
        'pxpHOeZp0I+i/Dmpw3LccK7Ix2W9e52QmEG9Od9cLZAruIjwHsJgpYRJBDDq7IXIbaDFjEryAs3Ac3iFcWAR7WSlL9iAoT6xD25XRMF9NJ0V0rqZghPrpPnc' .
        'mJSSxAdOAWUBIswjwnJjbbJsABuZJOw6aQ1RLMy9dXUsbk4L54WU6JxEnjEt0Voiz4iYaF8VrqQm4gMZmRjij+isA508/IqHrAUWUk+K3IqhFXUGd7L4uaXR' .
        'hUHe5bqmK+edHkWNTKdBT9fuR+qzjVoPO7FbEI1Y7MtKr8BKqxwtyCqpZsOlwXAQFs83J4XziXISnX6Bj5CV6Gyc+Wi8RDn5Md+TdFo28NsxnWWkwexL3zTw' .
        'eMF02yAYNt9p1OOlQ91yG96IGQMDafWJslAHt7JK6nAaTlkYzMZTS0Oq0glkNtbh+eYecnoAO9EJWQl7xabtvpOLxrf1h4XommKZ9akIKjQ93ipN/C0toVYh' .
        'CBAXwyVpLFhyfYbCBMtEIsMhvsOyNPJ1GHx6cfO7//jd/wPZkTFDCGMBAA==';

    /** @return array<int, array{service: string, prices: array<int, ?string>}> */
    public function rows(): array
    {
        $payload = base64_decode(self::COMPRESSED_ROWS, true);
        $json = $payload === false ? false : gzdecode($payload);

        if ($json === false) {
            throw new RuntimeException('No fue posible leer el catálogo interno de tarifas de Transporte.');
        }

        try {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('El catálogo interno de tarifas de Transporte no es válido.', previous: $exception);
        }
    }
}
