<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Farm;

class FarmImageSeeder extends Seeder
{
    public function run()
    {
        // 1. تفريغ الجدول لتجنب تكرار البيانات
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('farm_images')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. مصفوفة الصور الأصلية تبعتك (تم إزالة الـ ID فقط ليتولد تلقائياً، والاحتفاظ بـ farm_id كمرجع للألبومات)
        $originalImages = [
            ['farm_id' => 4, 'image_url' => 'farms/gallery/Sb3EJlxEIoXtqGPtk5z07UeNFmQqWG7ueHhlFrXr.jpg'],
            ['farm_id' => 4, 'image_url' => 'farms/gallery/1xVves5w6spsjAgdvk1oeE8y1jUnWoysLYqWZtWe.jpg'],
            ['farm_id' => 4, 'image_url' => 'farms/gallery/BLL3s7nRU2aoMBJePcfnL0GURE2WybfPe6s0L0Xa.jpg'],
            ['farm_id' => 4, 'image_url' => 'farms/gallery/961gKxBMa4AhNjTU2gK3O5SzQCb6oHODkMnkOUVY.jpg'],
            ['farm_id' => 4, 'image_url' => 'farms/gallery/auG3JokAamRIqD8ucgPHlOUhh7iRbTE3jppRBIbg.jpg'],
            ['farm_id' => 4, 'image_url' => 'farms/gallery/hJicBojEi2YG4LMihQVhlxtdPEInzMGplvbag9ib.jpg'],
            ['farm_id' => 6, 'image_url' => 'farms/gallery/dD0e1RlsY3zttFQuXrrwn7H3ddRCmpW5wPQRDCWH.jpg'],
            ['farm_id' => 6, 'image_url' => 'farms/gallery/PmK7pbrDHgTmUqAumQP9MeeKnozPczF33UJVp9N9.jpg'],
            ['farm_id' => 6, 'image_url' => 'farms/gallery/3vLwlCwTO0oBf8AvMPHM11bqXXqLpTupACXlZ0Nz.jpg'],
            ['farm_id' => 6, 'image_url' => 'farms/gallery/1AwQEK4ptzZHy5To4vEIfz1m46a9Hbs4tCYIzgp6.jpg'],
            ['farm_id' => 9, 'image_url' => 'farms/gallery/OHsPvphpu18sy1XRFihjDWibwP5vlAs87YJRf69G.jpg'],
            ['farm_id' => 9, 'image_url' => 'farms/gallery/Slf0gv4Lmtm9xo5H2Kgtd1HEHfMKfqSTW025dA5l.jpg'],
            ['farm_id' => 9, 'image_url' => 'farms/gallery/cGlzc5jBi46h2pD33peTE6fXjcXqDNpBXNx54xne.jpg'],
            ['farm_id' => 9, 'image_url' => 'farms/gallery/zU95PQ9jmgeaN44jKaW9OcJzzCzHfWaFF1MDSIBb.jpg'],
            ['farm_id' => 12, 'image_url' => 'farms/gallery/qTIRcuPfSj9Rj6WSAqVBaXjfPab109DoaMvmbf3Y.jpg'],
            ['farm_id' => 12, 'image_url' => 'farms/gallery/PrwYMSQf5ZNtzWwrITjUaKauBoGKss4GA3g5d6Rd.jpg'],
            ['farm_id' => 12, 'image_url' => 'farms/gallery/lW751dxmyFdsAFld8pNRqJOQfi9wCIqT3YSypAtQ.jpg'],
            ['farm_id' => 12, 'image_url' => 'farms/gallery/aaDpZxuJuWAUomyahnfO2lI3F5FIgQKUJ2czvAu6.jpg'],
            ['farm_id' => 14, 'image_url' => 'farms/gallery/pYaY7BM8fjocaCjkwBheqCZfaTcuyv0sKaTDnCI1.jpg'],
            ['farm_id' => 14, 'image_url' => 'farms/gallery/jiPI4DuO6PL4LnrivlDYCKM4BPTpd7KdFvFMQKRI.jpg'],
            ['farm_id' => 14, 'image_url' => 'farms/gallery/vcWTHugusY3tbhNOOScmBMi45SjWaD4D8fimqSB1.jpg'],
            ['farm_id' => 14, 'image_url' => 'farms/gallery/ThuT0xbSu8BFndYCi6eZ1DukE7FFOPbNs3nQKtSw.jpg'],
            ['farm_id' => 17, 'image_url' => 'farms/gallery/c42E6Fuv0qh1curYUZBoT4Nc4XRk8La3ToEPIw43.jpg'],
            ['farm_id' => 17, 'image_url' => 'farms/gallery/TnVhIUAB8brkHvCR1LvijN8kAF0wXowFFEi6GEA9.jpg'],
            ['farm_id' => 17, 'image_url' => 'farms/gallery/bVvZY3jjirYUNCa18nPQRNBoqnlQmvm62bt47vDD.jpg'],
            ['farm_id' => 17, 'image_url' => 'farms/gallery/40ADhuVXqsWhtmyBC9uA3ZBzT6CD6AbQZRjYxJNU.jpg'],
            ['farm_id' => 17, 'image_url' => 'farms/gallery/3LOU3GaGw6oUSr2SHhNiEyp32NHfqsRid8zXD7Yu.jpg'],
            ['farm_id' => 17, 'image_url' => 'farms/gallery/JSEYKQAANrGSPGGEVLNa0TBz3iUtaThBdUEEV6vR.jpg'],
            ['farm_id' => 17, 'image_url' => 'farms/gallery/bLbg2sIPq151o7PnrXkWPrGGVhmAHOd5FTz9n36i.jpg'],
            ['farm_id' => 3, 'image_url' => 'farms/gallery/mT159C6K4cTrq06GnV6TvsB5rwnnzenfpDtsiJ1u.jpg'],
            ['farm_id' => 3, 'image_url' => 'farms/gallery/wD8HYj18Gl4vU19ffzxgUvJMDPVkEiist0TYzoFk.jpg'],
            ['farm_id' => 3, 'image_url' => 'farms/gallery/HREVFQGSEVReeR3e6efr31tFxxZWya20LAhkFyfO.jpg'],
            ['farm_id' => 3, 'image_url' => 'farms/gallery/3JA0ISMu9K1j4q5OrSxJxC8HT6H5T74XZL6MH55x.jpg'],
            ['farm_id' => 8, 'image_url' => 'farms/gallery/JzLrvgTktAz524KMjhKdH2uXGeJmM8nDiaBeLJcm.jpg'],
            ['farm_id' => 8, 'image_url' => 'farms/gallery/9r87YIMncVRkC5hsvaF057PzRhtxrI1Ui7dXeGyL.jpg'],
            ['farm_id' => 8, 'image_url' => 'farms/gallery/6QHiVtGa1F7Dj0PoWU6MB1NyK1kihWgbUsQaqfnE.jpg'],
            ['farm_id' => 8, 'image_url' => 'farms/gallery/h6QflxClV48TDweq2lHyFTP2JDiOg735BRgCZPsJ.jpg'],
            ['farm_id' => 8, 'image_url' => 'farms/gallery/9BaVlhzPiBH6g2nq7mHLXULe1IreZ0THTdeh4FkR.jpg'],
            ['farm_id' => 8, 'image_url' => 'farms/gallery/BsH9O6UswFiVI4uc4Msdw9TdmeZSmfzgTCrtofAw.jpg'],
            ['farm_id' => 11, 'image_url' => 'farms/gallery/3txu2bnAD2x1E8AuPOJ2X25UzAquNpcxhIzsmZk8.jpg'],
            ['farm_id' => 11, 'image_url' => 'farms/gallery/Q9HHnZlxVwK2Up5HGAi31eZhjG0zn3970rwvVEaQ.jpg'],
            ['farm_id' => 11, 'image_url' => 'farms/gallery/TjH6kHn5R7Zr5F0mCpD5wwpgRW9rshRPubeYN7Md.jpg'],
            ['farm_id' => 11, 'image_url' => 'farms/gallery/eSZ2C9EiUnPt2WUfZeoOKXWhhhgtE47NdWobvrU2.jpg'],
            ['farm_id' => 22, 'image_url' => 'farms/gallery/nEoAsBxkwq1JD7WKeBRzKIq19yFKHIARzNwZP7GW.jpg'],
            ['farm_id' => 22, 'image_url' => 'farms/gallery/vewX7MsIgu49me2W0LvVeZCxrSTXXoyEX6FrGZ8a.jpg'],
            ['farm_id' => 22, 'image_url' => 'farms/gallery/PlP5ENlJ0ejquFoA0mdtb1Fil6awpoHMYylfD5cx.jpg'],
            ['farm_id' => 22, 'image_url' => 'farms/gallery/pRnc6C74woQ5wOR85WhjSmqEaDCSgCFYMNvASluc.jpg'],
            ['farm_id' => 22, 'image_url' => 'farms/gallery/ZGvyjNmi6pAzIZLLE3BhjMJe9RTvG6cHQyccA6qo.jpg'],
            ['farm_id' => 22, 'image_url' => 'farms/gallery/XqPeSR41uRBLqxGXrk5mV6tdaRkxueJyYkUFtNF0.jpg'],
            ['farm_id' => 22, 'image_url' => 'farms/gallery/il4HazGmlmodifSNpVUUQyt4RdOR1eD4sYi1rI6V.jpg'],
            ['farm_id' => 22, 'image_url' => 'farms/gallery/cuhA7jtusx4zNjXjZRErwo2PwvbDIHNhAOHB9EXO.jpg'],
            ['farm_id' => 23, 'image_url' => 'farms/gallery/qDziyruKuRwXEbN2sGcypg4KxstfwLOzbtA93H0f.jpg'],
            ['farm_id' => 23, 'image_url' => 'farms/gallery/MYFxlrMhqi2YIUe0Nun6PS93D1bDtOUJ2uexTfgV.jpg'],
            ['farm_id' => 23, 'image_url' => 'farms/gallery/nQmKKeBTzyfGuwapNveYh3YMHudooFsPJUGoCyhB.jpg'],
            ['farm_id' => 23, 'image_url' => 'farms/gallery/ouC2KcVgcZjGiNwZpenFAJAs68zltPpS1SF4w6wq.jpg'],
            ['farm_id' => 23, 'image_url' => 'farms/gallery/u85KP0bH0GYk5Fb0yd6dSsp2zZeDNHcgxW4tOg9Y.jpg'],
            ['farm_id' => 1, 'image_url' => 'farms/gallery/m4fvjIFbdVR6DctiT88lZWbNOYia44kHrc3wRUv1.jpg'],
            ['farm_id' => 1, 'image_url' => 'farms/gallery/CWdsNYW2bcaNQ74bYNAhnerAFiCP5fQrT78llBOL.jpg'],
            ['farm_id' => 1, 'image_url' => 'farms/gallery/3HkHiRpJgbFHQs1p4KHCOEZoIuYc8WjpDIwDtVts.jpg'],
            ['farm_id' => 1, 'image_url' => 'farms/gallery/bBVrGShELFQoSTlFA8CWuXARmUjYV7cVhdk9lQ8i.jpg'],
            ['farm_id' => 1, 'image_url' => 'farms/gallery/sWKXEkJv0jbd1DDDWYZP1EoayAoCTMEk6MzGYpG8.jpg'],
            ['farm_id' => 2, 'image_url' => 'farms/gallery/484WvVsFIz8WPE8XLmO1Abs5Doh2huIaZ73HCH7t.jpg'],
            ['farm_id' => 2, 'image_url' => 'farms/gallery/UcLifUihpZqVSNpTIBuyk3YSJSW4CIdGx7ea6DR3.jpg'],
            ['farm_id' => 2, 'image_url' => 'farms/gallery/dpTerqDOHy11W1WgOntpFoCT5iHAJ0kbPbrlF4sa.jpg'],
            ['farm_id' => 2, 'image_url' => 'farms/gallery/TyxbIa1rpVtqTwN2DlCZYW1s3HcF4unu7jW3z8R6.jpg'],
            ['farm_id' => 2, 'image_url' => 'farms/gallery/nMsfW9ffEywROhjQaKUG5Go3kezy98FYxX4rznKP.jpg'],
            ['farm_id' => 5, 'image_url' => 'farms/gallery/20kQsb9NCDnWKU33lJA7SWaNy8dmtGPtzxeZ0G8J.jpg'],
            ['farm_id' => 5, 'image_url' => 'farms/gallery/UHCJUsxMha32KOmCQ0MmHQlAb1G6cWsgfMHRi53Y.jpg'],
            ['farm_id' => 5, 'image_url' => 'farms/gallery/la7pAmfYNIhml1OesdnEOF75d6y8j21HxMPEIhMa.jpg'],
            ['farm_id' => 5, 'image_url' => 'farms/gallery/yjk6PskPV6kpgtGqbhAndc5N376saWfslSCy88DA.jpg'],
            ['farm_id' => 5, 'image_url' => 'farms/gallery/Rq09iSdepyPvu7ebDikIrn7PCJlfEmRBm9IVLcdv.jpg'],
            ['farm_id' => 13, 'image_url' => 'farms/gallery/S7JksS46rP2v2cejv4uCipSrVUw8aCui0do25RSb.jpg'],
            ['farm_id' => 13, 'image_url' => 'farms/gallery/Qmk5ThVosYO0LJWE3cPAzv5c8XueaE7PEzjBIJTe.jpg'],
            ['farm_id' => 13, 'image_url' => 'farms/gallery/AgfFoAcMnvwsFJxsWACB1LJm4R8UBTykpvo8NGBF.jpg'],
            ['farm_id' => 13, 'image_url' => 'farms/gallery/kEMmii4F2c1niDltQfY5RVTupgSzdpKdYwaJninb.jpg'],
            ['farm_id' => 13, 'image_url' => 'farms/gallery/FWuNB7oPJL1hDo3weNM7o2fM068C0Nwb97ioiJxj.jpg'],
            ['farm_id' => 15, 'image_url' => 'farms/gallery/U71vIgGpx4IAiCP4X0DnVVEREXOUKivravxq2r8I.jpg'],
            ['farm_id' => 15, 'image_url' => 'farms/gallery/2MMBjTBLhFwXKOf5U6bcvqegH3pLUmFzwK6Tbmx4.jpg'],
            ['farm_id' => 15, 'image_url' => 'farms/gallery/FtxJibH0wsdDg7kvQIEa7yUnFLoymuA7eb61cJwj.jpg'],
            ['farm_id' => 15, 'image_url' => 'farms/gallery/ykPXbjFFlhkgSS5GXbWG6NOmDXjPJkrOgCvtF6mF.jpg'],
            ['farm_id' => 16, 'image_url' => 'farms/gallery/z8xSH9eF1YQiJyTEF5eDv4w5ruDpiyijcowWAQnT.jpg'],
            ['farm_id' => 16, 'image_url' => 'farms/gallery/cEk5doAMfu684K82xdgI9tFixFnBxcewHKIF3xaO.jpg'],
            ['farm_id' => 16, 'image_url' => 'farms/gallery/sBMMZNTjQ5ElOL8sfFvBcUEad1x2pQDRsPGXZ9iw.jpg'],
            ['farm_id' => 16, 'image_url' => 'farms/gallery/9rm7O4Q0aXM6KxrlIYM2gMh1Wlnl0zZU7dHdKkkO.jpg'],
            ['farm_id' => 16, 'image_url' => 'farms/gallery/1mlplyFk1bkH8lZ0xvAb5UdgRm6I7f2EvykM1wgM.jpg'],
            ['farm_id' => 16, 'image_url' => 'farms/gallery/LYhQzqoabiD6a5FkCCgZ375xBZPoo7Jq3WmUrr6v.jpg'],
            ['farm_id' => 18, 'image_url' => 'farms/gallery/1UULjpJMh3IJHNSu3apu9m0z7ns5rMpSs4R4gAr5.jpg'],
            ['farm_id' => 18, 'image_url' => 'farms/gallery/dKUcciup7ArGMsBorrhN5XfGy8bS7SCH94hEYoPl.jpg'],
            ['farm_id' => 18, 'image_url' => 'farms/gallery/dMXQW1JI3h1E5GaYPLGhXcE9bytSAYfE4Yolxw74.jpg'],
            ['farm_id' => 18, 'image_url' => 'farms/gallery/amGZyPDNfZvizU7myba1ov9P5w6Om2hgRAJ5L1cA.jpg'],
            ['farm_id' => 18, 'image_url' => 'farms/gallery/V7fRsz2SJDB5qlFcHgDTp0axvEYkFCRQSgaaWhNA.jpg'],
            ['farm_id' => 19, 'image_url' => 'farms/gallery/iR5r5HfAn7YHmhSdmOTc7VfQCgkXpun5qvV1Ty8X.jpg'],
            ['farm_id' => 19, 'image_url' => 'farms/gallery/aZqY5aoPObEwGUmC3qZWTkkPbFL6Ec27e1b7hTyP.jpg'],
            ['farm_id' => 19, 'image_url' => 'farms/gallery/ex2td3POic8JH98Xg3BxhrcbLRGa3KsefwmmyTaJ.jpg'],
            ['farm_id' => 19, 'image_url' => 'farms/gallery/bJKUCYC9xr0VIsw8wh7llRKI94Gq6yMCDfYa5sPe.jpg'],
            ['farm_id' => 19, 'image_url' => 'farms/gallery/Oos8rvVVHp66wpxkfK38g9vlNBxEO4rYar6eSOSO.jpg'],
            ['farm_id' => 21, 'image_url' => 'farms/gallery/KzqTYfUnnMbbuEzfLIZ0TpEJim8FppFiSb7cLRdt.jpg'],
            ['farm_id' => 21, 'image_url' => 'farms/gallery/7gtJIDSlmRzyDNoyRXWlRIWyfHPYcp59zqTvBz0m.jpg'],
            ['farm_id' => 21, 'image_url' => 'farms/gallery/B2UoFprwYfdwvkJYDzkqL1iK9vhG6dzPChMxv6Fm.jpg'],
            ['farm_id' => 21, 'image_url' => 'farms/gallery/wrheAbU1NO8W7b6isO3s07MNUlA9mz3xQie48hti.jpg'],
            ['farm_id' => 21, 'image_url' => 'farms/gallery/82Dit0VgNNpmfqk84vuRHzEIJizKai0HpoVuV3TV.jpg'],
            ['farm_id' => 21, 'image_url' => 'farms/gallery/HqZphhhLXeB24kvFInk0ybCQtUhMrd0D5vaZZASp.jpg'],
            ['farm_id' => 21, 'image_url' => 'farms/gallery/DI7WnZ72YBF3ymZPQ4rm4dtAnGF1O6bio8FBuoLr.jpg'],
            ['farm_id' => 21, 'image_url' => 'farms/gallery/2dIuawqqwwPGH9iovlXufEqAhBnTxExwxl4nk5Y2.jpg'],
            ['farm_id' => 21, 'image_url' => 'farms/gallery/yv75VhXMPxxNZXBIWKsvOxRARO0FPlti7IOLfWFq.jpg'],
            ['farm_id' => 24, 'image_url' => 'farms/gallery/x89iZIgRzBBjEfGjEmZ9zNOMi5EeuTVMIp8oaNF9.jpg'],
            ['farm_id' => 24, 'image_url' => 'farms/gallery/CfWFnC6UZtFbi2qaNtEExGvkkia2iq6XKnJb6OFN.jpg'],
            ['farm_id' => 24, 'image_url' => 'farms/gallery/L0Zj7XHOqIIWOERMIFeVyacHMF1fPo8cr9JGL8xs.jpg'],
            ['farm_id' => 24, 'image_url' => 'farms/gallery/SRMLa00Yy2dO12uVgSNBvs8sO3ZBJZZ9W8PW4bQb.jpg'],
            ['farm_id' => 7, 'image_url' => 'farms/gallery/ADaPe7R5S8duaCK9ldSndb6XxvwELF6o6XIeyZFr.jpg'],
            ['farm_id' => 7, 'image_url' => 'farms/gallery/5I48wwVdXn9UDaByIhjWUW0NZD3cxXhLsPLpQ9FN.jpg'],
            ['farm_id' => 7, 'image_url' => 'farms/gallery/8ljEjaan96h56JwUTsS2yJfFDIcqNnRrBybWOGlP.jpg'],
            ['farm_id' => 7, 'image_url' => 'farms/gallery/W0bqOtkQU3q0MgER9rcMPEZNAf6y3lTpCqFUh31o.jpg'],
            ['farm_id' => 10, 'image_url' => 'farms/gallery/oBXczw7j8IwknTMPaNVauPabiOJhdNaG9kJMM2cA.jpg'],
            ['farm_id' => 10, 'image_url' => 'farms/gallery/6ziaDSZR67x3MYS4NDcsusNEALYFvWFUcqjL5ZV9.jpg'],
            ['farm_id' => 10, 'image_url' => 'farms/gallery/azZCVTozqEOLaM6mqKkuudzqlMu1mUtLVSxABfI0.jpg'],
            ['farm_id' => 10, 'image_url' => 'farms/gallery/lH2tDh1KFgs0TegvniJyAIzFR27FqZTHrQIZRde5.jpg'],
            ['farm_id' => 20, 'image_url' => 'farms/gallery/vjX75TaXDnWo9kSaoVO5K3YJacjKLa21Ev2Z9DlS.jpg'],
            ['farm_id' => 20, 'image_url' => 'farms/gallery/qdxicxcyrjVMlJKdzW8otE0vOpgHS8rJpb3ONZi0.jpg'],
            ['farm_id' => 20, 'image_url' => 'farms/gallery/HrJZY5cyPcwPDtPJKpY3OC7AMqc1NvPLHS9KqjMA.jpg'],
            ['farm_id' => 20, 'image_url' => 'farms/gallery/I7lrXYdHMzlmolwFVj5difBXrJqE3uMKDstO6wrb.jpg'],
        ];

        // 3. تجميع الصور في "ألبومات" حسب رقم المزرعة الأصلية
        // النتيجة: مصفوفة بـ 24 ألبوم، كل ألبوم بيحتوي على صور متناسقة لنفس المكان
        $albums = [];
        foreach ($originalImages as $img) {
            $albums[$img['farm_id']][] = $img['image_url'];
        }

        // 4. جلب جميع المزارع الموجودة في قاعدة البيانات (الـ 274 مزرعة)
        $farms = Farm::orderBy('id')->get();
        $insertData = [];

        // 5. ربط كل مزرعة بألبوم متناسق كامل
        foreach ($farms as $farm) {

            // المعادلة السحرية:
            // إذا المزرعة من الـ 24 الأصليات، بتاخد ألبومها الخاص.
            // إذا مزرعة جديدة (مثلاً رقم 25)، بتاخد ألبوم مزرعة رقم 1، المزرعة 26 بتاخد ألبوم 2، وهكذا.
            $assignedAlbumId = ($farm->id <= 24) ? $farm->id : (($farm->id - 1) % 24) + 1;

            // التأكد من وجود الألبوم (لتفادي أي خطأ برمجي)
            if (isset($albums[$assignedAlbumId])) {
                foreach ($albums[$assignedAlbumId] as $url) {
                    $insertData[] = [
                        'farm_id'    => $farm->id,
                        'image_url'  => $url,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // 6. الإدخال السريع للداتابيس (Bulk Insert)
        $chunks = array_chunk($insertData, 500);
        foreach ($chunks as $chunk) {
            DB::table('farm_images')->insert($chunk);
        }

        $this->command->info('تم زراعة ' . count($insertData) . ' صورة بنجاح! تم الحفاظ على تناسق الألبومات ومنع العشوائية.');
    }
}
