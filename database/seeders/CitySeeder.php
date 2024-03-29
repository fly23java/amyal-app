<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            ['name_arabic' => ' الرياض ' , 'name_english' => ' RIYADH ' , 'region_id' => 1],
            ['name_arabic' => ' الدرعية ' , 'name_english' => ' ALDEREIAH ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻟﺪﻭﺍﺩمي ' , 'name_english' => ' AL-DWADMY ' , 'region_id' => 1],
            ['name_arabic' => ' القويعيه ' , 'name_english' => ' AL-KWAYEYA ' , 'region_id' => 1],
            ['name_arabic' => ' ﻭﺍﺩﻯ ﺍﻟﺪﻭﺍسر ' , 'name_english' => ' WADY AL-DAWASER ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻟﻤﺠﻤﻌﻪ ' , 'name_english' => ' AL-MAJMAA ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻟﺨرج ' , 'name_english' => ' AL-KHARJ ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻻﻓﻼﺝ ' , 'name_english' => ' AL-AFLAJ ' , 'region_id' => 1],
            ['name_arabic' => ' ﺷﻘﺮﺍﺀ ' , 'name_english' => ' SHAKRAA ' , 'region_id' => 1],
            ['name_arabic' => ' ﺣﻮﻃﺔ بني تميم ' , 'name_english' => ' HAWTAT BNEY TAMEEM ' , 'region_id' => 1],
            ['name_arabic' => ' الزلفي ' , 'name_english' => ' AL-ZLFA ' , 'region_id' => 1],
            ['name_arabic' => ' ﺭﻣﺎﺡ ' , 'name_english' => ' ROMAH ' , 'region_id' => 1],
            ['name_arabic' => ' عفيف ' , 'name_english' => ' AFIF ' , 'region_id' => 1],
            ['name_arabic' => ' حريملاء ' , 'name_english' => ' HRYMLAA ' , 'region_id' => 1],
            ['name_arabic' => ' ﺛﺎﺩﻕ ' , 'name_english' => ' THADEK ' , 'region_id' => 1],
            ['name_arabic' => ' المزاحمية ' , 'name_english' => ' AL-MZAHMYA ' , 'region_id' => 1],
            ['name_arabic' => ' السليل ' , 'name_english' => ' AL-SALEEL ' , 'region_id' => 1],
            ['name_arabic' => ' ضرماء ' , 'name_english' => ' DARMAA ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻟﻐﺎﻁ ' , 'name_english' => ' AL-GHAT ' , 'region_id' => 1],
            ['name_arabic' => ' الحريق ' , 'name_english' => ' Al-Hareeg ' , 'region_id' => 1],
            ['name_arabic' => ' ﻣﻜﻪ ﺍﻟﻤﻜﺮﻣﻪ ' , 'name_english' => ' MAKKAH ' , 'region_id' => 2],
            ['name_arabic' => ' ﺟﺪﻩ ' , 'name_english' => ' JEDDAH ' , 'region_id' => 2],
            ['name_arabic' => ' ﺍﻟﻄﺎﺋﻒ ' , 'name_english' => ' TAIF ' , 'region_id' => 2],
            ['name_arabic' => ' ﺍﻟﻘﻨﻔﺬﻩ ' , 'name_english' => ' AL-KONFOTHA ' , 'region_id' => 2],
            ['name_arabic' => ' الليث ' , 'name_english' => ' ALLAITH ' , 'region_id' => 2],
            ['name_arabic' => ' ﺭﺍﺑﻎ ' , 'name_english' => ' RABEGH ' , 'region_id' => 2],
            ['name_arabic' => ' ﺍﻟﺠﻤﻮﻡ ' , 'name_english' => ' AL-GMOUM ' , 'region_id' => 2],
            ['name_arabic' => ' رنية ' , 'name_english' => ' RANYAH ' , 'region_id' => 2],
            ['name_arabic' => ' تربة ' , 'name_english' => ' TRBAH ' , 'region_id' => 2],
            ['name_arabic' => ' الكامل ' , 'name_english' => ' AL-KAMEL ' , 'region_id' => 2],
            ['name_arabic' => ' الخرمه ' , 'name_english' => ' ALKHARMA ' , 'region_id' => 2],
            ['name_arabic' => ' ﺍﻟﻤﺪﻳﻨﻪ ﺍﻟﻤﻨﻮﺭﻩ ' , 'name_english' => ' MADINAH ' , 'region_id' => 3],
            ['name_arabic' => ' ينبع ' , 'name_english' => ' YANBU ' , 'region_id' => 3],
            ['name_arabic' => ' ﺍﻟﻌﻼ ' , 'name_english' => ' AL-OLA ' , 'region_id' => 3],
            ['name_arabic' => ' ﺍﻟﻤﻬﺪ ' , 'name_english' => ' AL-MAHD ' , 'region_id' => 3],
            ['name_arabic' => ' الحناكية ' , 'name_english' => ' AL-HNAKYAH ' , 'region_id' => 3],
            ['name_arabic' => ' ﺧﻴبر ' , 'name_english' => ' KHAYBAR ' , 'region_id' => 3],
            ['name_arabic' => ' بدر ' , 'name_english' => ' BADER ' , 'region_id' => 3],
            ['name_arabic' => ' بريده ' , 'name_english' => ' BURAYDAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﻋنيزة ' , 'name_english' => ' ENEZAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻟﺮﺱ ' , 'name_english' => ' ALRAS ' , 'region_id' => 4],
            ['name_arabic' => ' البكيرية ' , 'name_english' => ' AL-BKERYA ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻟﻤﺬﻧﺐ ' , 'name_english' => ' AL-MZNB ' , 'region_id' => 4],
            ['name_arabic' => ' البدائع ' , 'name_english' => ' AL-BADAEE ' , 'region_id' => 4],
            ['name_arabic' => ' النبهانية ' , 'name_english' => ' AL-NBHANIAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﺭياض الخبرا ' , 'name_english' => ' RIYADH ALKHABRA ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻻسياﺡ ' , 'name_english' => ' AL-ASYAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﻋﻴﻮﻥ ﺍﻟﺠﻮﺍﺀ ' , 'name_english' => ' AOWYOON ALJIWA ' , 'region_id' => 4],
            ['name_arabic' => ' الشماسية ' , 'name_english' => ' AL-SHMASYAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻟﺪﻣﺎﻡ ' , 'name_english' => ' DAMMAM ' , 'region_id' => 5],
            ['name_arabic' => ' القطيف ' , 'name_english' => ' AL-KATEEF ' , 'region_id' => 5],
            ['name_arabic' => ' ﺣﻔﺮ الباطن ' , 'name_english' => ' HAFR ALBATIN ' , 'region_id' => 5],
            ['name_arabic' => ' ﺍﻟﺨبر ' , 'name_english' => ' AL-KHUBAR ' , 'region_id' => 5],
            ['name_arabic' => ' النعيرية ' , 'name_english' => ' AL-NAERYAH ' , 'region_id' => 5],
            ['name_arabic' => ' قريه ' , 'name_english' => ' KARYA ' , 'region_id' => 5],
            ['name_arabic' => ' بقيق ' , 'name_english' => ' BAKEEK ' , 'region_id' => 5],
            ['name_arabic' => ' ﺍﻟﺨﻔجي ' , 'name_english' => ' ALKHAFJI ' , 'region_id' => 5],
            ['name_arabic' => ' الجبيل ' , 'name_english' => ' AL-GBEIL ' , 'region_id' => 5],
            ['name_arabic' => ' ﺍﺑﻬﺎ ' , 'name_english' => ' ABHA ' , 'region_id' => 6],
            ['name_arabic' => ' ﺧميس ﻣشيط ' , 'name_english' => ' KHAMEES MSHEET ' , 'region_id' => 6],
            ['name_arabic' => ' بيشه ' , 'name_english' => ' BESHAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﻣﺤﺎﺋﻞ عسير ' , 'name_english' => ' MAHAEL ASEER ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻟﻨﻤﺎﺹ ' , 'name_english' => ' AL-NMAS ' , 'region_id' => 6],
            ['name_arabic' => ' أحدرفيدة ' , 'name_english' => ' OHOD RFEDAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﻇﻬﺮﺍﻥ ﺍﻟﺠﻨﻮﺏ ' , 'name_english' => ' ZAHRAN AL-JANOUB ' , 'region_id' => 6],
            ['name_arabic' => ' بلقرن ' , 'name_english' => ' BLKARN ' , 'region_id' => 6],
            ['name_arabic' => ' سراة عبيدة ' , 'name_english' => ' SRAT EBEIDAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻟﻤﺠﺎﺭﺩﺓ ' , 'name_english' => ' AL-MJARDAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﺭﺟﺎﻝ ﺃﻟﻤﻊ ' , 'name_english' => ' REJAL ALMAA ' , 'region_id' => 6],
            ['name_arabic' => ' ﺗﺜليث ' , 'name_english' => ' TATHLEETH ' , 'region_id' => 6],
            ['name_arabic' => ' ﺣﺎﺋﻞ ' , 'name_english' => ' HAIL ' , 'region_id' => 7],
            ['name_arabic' => ' بقعاء ' , 'name_english' => ' BAKAA ' , 'region_id' => 7],
            ['name_arabic' => ' ﺍﻟﻐﺰﺍﻟﺔ ' , 'name_english' => ' AL-GHAZALAH ' , 'region_id' => 7],
            ['name_arabic' => ' ﺍﻟﺸﻨﺎﻥ ' , 'name_english' => ' AL-SHNAN ' , 'region_id' => 7],
            ['name_arabic' => ' ﺗﺒﻮﻙ ' , 'name_english' => ' TABOUK ' , 'region_id' => 8],
            ['name_arabic' => ' ﺿباء ' , 'name_english' => ' DEBAA ' , 'region_id' => 8],
            ['name_arabic' => ' تيماء ' , 'name_english' => ' TAIMAA ' , 'region_id' => 3],
            ['name_arabic' => ' ﺍﻟﻮﺟﻪ ' , 'name_english' => ' ALWJH ' , 'region_id' => 8],
            ['name_arabic' => ' ﺣﻘﻞ ' , 'name_english' => ' HAKL ' , 'region_id' => 8],
            ['name_arabic' => ' ﺃﻣﻠﺞ ' , 'name_english' => ' AMLJ ' , 'region_id' => 8],
            ['name_arabic' => ' الباحه ' , 'name_english' => ' BAHA ' , 'region_id' => 9],
            ['name_arabic' => ' بلجرسي ' , 'name_english' => ' BLGRSHY ' , 'region_id' => 9],
            ['name_arabic' => ' ﺍﻟﻤﺨﻮﺍﺓ ' , 'name_english' => ' AL-MKHWAH ' , 'region_id' => 9],
            ['name_arabic' => ' ﺍﻟﻤﻨﺪﻕ ' , 'name_english' => ' AL-MANDAK ' , 'region_id' => 9],
            ['name_arabic' => ' ﺍﻟﻌﻘﻴﻖ ' , 'name_english' => ' AL-AKEEK ' , 'region_id' => 9],
            ['name_arabic' => ' ﻗﻠﻮﻩ ' , 'name_english' => ' KALWAH ' , 'region_id' => 9],
            ['name_arabic' => ' ﺍﻟﻘﺮﻯ ' , 'name_english' => ' ALQORA ' , 'region_id' => 9],
            ['name_arabic' => ' ﻋﺮﻋﺮ ' , 'name_english' => ' AR AR ' , 'region_id' => 10],
            ['name_arabic' => ' ﺭﻓﺤﺎﺀ ' , 'name_english' => ' RAFHAA ' , 'region_id' => 10],
            ['name_arabic' => ' طريف ' , 'name_english' => ' TAREEF ' , 'region_id' => 10],
            ['name_arabic' => ' القريات ' , 'name_english' => ' ALQURAYAT ' , 'region_id' => 11],
            ['name_arabic' => ' ﺩﻭﻣﺔ ﺍﻟﺠﻨﺪﻝ ' , 'name_english' => ' DAWMAT AL-JANDAL ' , 'region_id' => 11],
            ['name_arabic' => ' ﺟﺎﺯﺍﻥ ' , 'name_english' => ' JAZAN ' , 'region_id' => 12],
            ['name_arabic' => ' صبياء ' , 'name_english' => ' SBYAA ' , 'region_id' => 12],
            ['name_arabic' => ' ﺻﺎﻣﻄﻪ ' , 'name_english' => ' SAMTAH ' , 'region_id' => 12],
            ['name_arabic' => ' ﺍﻟﺪﺍﺋﺮ ' , 'name_english' => ' AL-DAAER ' , 'region_id' => 12],
            ['name_arabic' => ' العبدابي ' , 'name_english' => ' AL-EDABE ' , 'region_id' => 12],
            ['name_arabic' => ' بيش ' , 'name_english' => ' BEESH ' , 'region_id' => 12],
            ['name_arabic' => ' ﺍﻟﻌﺎﺭﺿﺔ ' , 'name_english' => ' AL-AARDAH ' , 'region_id' => 12],
            ['name_arabic' => ' ﺍلريث ' , 'name_english' => ' AL-RETH ' , 'region_id' => 12],
            ['name_arabic' => ' ﺿﻤﺪ ' , 'name_english' => ' DMD ' , 'region_id' => 12],
            ['name_arabic' => ' ﻧﺠﺮﺍﻥ ' , 'name_english' => ' NAJRAN ' , 'region_id' => 13],
            ['name_arabic' => ' شرورة ' , 'name_english' => ' SHAROURAH ' , 'region_id' => 13],
            ['name_arabic' => ' بدرﺍﻟﺠﻨﻮﺏ ' , 'name_english' => ' BADER AL-GANOB ' , 'region_id' => 13],
            ['name_arabic' => ' ﺣﺒﻮﻧﺎ ' , 'name_english' => ' HBOUNA ' , 'region_id' => 13],
            ['name_arabic' => ' ﺛﺎﺭ ' , 'name_english' => ' THAR ' , 'region_id' => 13],
            ['name_arabic' => ' يدمه ' , 'name_english' => ' YADMAH ' , 'region_id' => 13],
            ['name_arabic' => ' خباش ' , 'name_english' => ' KBASH ' , 'region_id' => 13],
            ['name_arabic' => ' ﺍﻟﻈﻬﺮﺍﻥ ' , 'name_english' => ' AL-ZAHRAN ' , 'region_id' => 5],
            ['name_arabic' => ' ﺳﻴﻬﺎﺕ ' , 'name_english' => ' SEEHAT ' , 'region_id' => 5],
            ['name_arabic' => ' ﺭﺃﺱ ﺗﻨﻮﺭﺓ ' , 'name_english' => ' RAAS TANOURAH ' , 'region_id' => 5],
            ['name_arabic' => ' ﺻﻔﻮﻯ ' , 'name_english' => ' SAFWA ' , 'region_id' => 5],
            ['name_arabic' => ' سكاكا ' , 'name_english' => ' SKAKA ' , 'region_id' => 11],
            ['name_arabic' => ' العويقيلة ' , 'name_english' => ' AL-OAYKILAH ' , 'region_id' => 10],
            ['name_arabic' => ' ابي عريش ' , 'name_english' => ' ABY AREESH ' , 'region_id' => 12],
            ['name_arabic' => ' ﺍﺣﺪﺍﻟﻤﺴﺎﺭﺣﺔ ' , 'name_english' => ' OHOD AL-MASARHA ' , 'region_id' => 12],
            ['name_arabic' => ' ﻓﺮﺳﺎﻥ ' , 'name_english' => ' FRSAN ' , 'region_id' => 12],
            ['name_arabic' => ' الخرخير ' , 'name_english' => ' AL-KHARKHER ' , 'region_id' => 13],
            ['name_arabic' => ' طبرجل ' , 'name_english' => ' TABARJAL ' , 'region_id' => 11],
            ['name_arabic' => ' البدع ' , 'name_english' => ' AL-BEDE ' , 'region_id' => 8],
            ['name_arabic' => ' ﺍﻟﺪﺭﺏ ' , 'name_english' => ' AL-DARB ' , 'region_id' => 12],
            ['name_arabic' => ' ﺍﻟﺸﻘﻴﻖ بجازان ' , 'name_english' => ' AL-SHKEK BJEZAN ' , 'region_id' => 12],
            ['name_arabic' => ' ﺳﺪﻳﺮ ' , 'name_english' => ' SDER ' , 'region_id' => 1],
            ['name_arabic' => ' شعبة الجنسية ' , 'name_english' => ' SHOABAT AL-GENSYAH ' , 'region_id' => 1],
            ['name_arabic' => ' ﺣﻮﻃﺔ ﺳﺪﻳﺮ ' , 'name_english' => ' HAWTET SDER ' , 'region_id' => 1],
            ['name_arabic' => ' تمير ' , 'name_english' => ' TAMEIR ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻟﺮويضة ' , 'name_english' => ' AL-RWEDAH ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻟﺪﻟﻢ ' , 'name_english' => ' AL-DLM ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻟﺠﻤﺶ ' , 'name_english' => ' AL-JAMSH ' , 'region_id' => 1],
            ['name_arabic' => ' ﺳﺎﺟﺮ ' , 'name_english' => ' SAJER ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻻﺭﻃﺎوية ' , 'name_english' => ' AL-ARTAWYAH ' , 'region_id' => 1],
            ['name_arabic' => ' ﻣﺮﺍﺕ ' , 'name_english' => ' MRAT ' , 'region_id' => 1],
            ['name_arabic' => ' حلبان ' , 'name_english' => ' HALBAN ' , 'region_id' => 1],
            ['name_arabic' => ' الرين ' , 'name_english' => ' AL-RIAN ' , 'region_id' => 1],
            ['name_arabic' => ' طحي ' , 'name_english' => ' THI ' , 'region_id' => 1],
            ['name_arabic' => ' الحوميات ' , 'name_english' => ' AL-HOMIAT ' , 'region_id' => 1],
            ['name_arabic' => ' نفي ' , 'name_english' => ' NFI ' , 'region_id' => 1],
            ['name_arabic' => ' ﻣﺸﺎﺵ ﻋﻮﺽ ' , 'name_english' => ' MSHASH AWAD ' , 'region_id' => 1],
            ['name_arabic' => ' القاعية ' , 'name_english' => ' AL-QAEAH ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻟﺤﺼﺎﺓ ' , 'name_english' => ' AL-HASAT ' , 'region_id' => 1],
            ['name_arabic' => ' ﺣﻔﺮ العتش ' , 'name_english' => ' HAFER AL-ATCH ' , 'region_id' => 1],
            ['name_arabic' => ' الحيانية ' , 'name_english' => ' AL-HYANIAH ' , 'region_id' => 1],
            ['name_arabic' => ' البجادية ' , 'name_english' => ' AL-BJADEAH ' , 'region_id' => 1],
            ['name_arabic' => ' ﻋﻨﺎﻥ ' , 'name_english' => ' ANA ' , 'region_id' => 1],
            ['name_arabic' => ' ﺟﻮ ' , 'name_english' => ' JO ' , 'region_id' => 1],
            ['name_arabic' => ' ﺍﻟﺠﻠﺔ ونبراك ' , 'name_english' => ' AL-JALEH WATBRAK ' , 'region_id' => 1],
            ['name_arabic' => ' ﻋﺮﻭﻯ ' , 'name_english' => ' ARWA ' , 'region_id' => 1],
            ['name_arabic' => ' صبحاء ' , 'name_english' => ' SBAHA ' , 'region_id' => 1],
            ['name_arabic' => ' لبخه ' , 'name_english' => ' LABKAH ' , 'region_id' => 1],
            ['name_arabic' => ' ذهبان ' , 'name_english' => ' DHAHBAN ' , 'region_id' => 2],
            ['name_arabic' => ' المويه ' , 'name_english' => ' ALUMAIH ' , 'region_id' => 2],
            ['name_arabic' => ' ﺤﺮﺓ ' , 'name_english' => ' BHRAH ' , 'region_id' => 2],
            ['name_arabic' => ' الشعيبة ' , 'name_english' => ' AL-SHEEBAH ' , 'region_id' => 2],
            ['name_arabic' => ' ﺍﻟﺸميسي ' , 'name_english' => ' AL-SHEMESY ' , 'region_id' => 2],
            ['name_arabic' => ' العرضية الجنوبية ' , 'name_english' => ' AL-ARDYAH AL-JANOUBYAH ' , 'region_id' => 2],
            ['name_arabic' => ' ﺛﻮﻝ ' , 'name_english' => ' THOUL ' , 'region_id' => 2],
            ['name_arabic' => ' ﻇﻠﻢ ' , 'name_english' => ' DLM ' , 'region_id' => 2],
            ['name_arabic' => ' الزيمة ' , 'name_english' => ' AL-ZEMAH ' , 'region_id' => 2],
            ['name_arabic' => ' بقياء ' , 'name_english' => ' BQYA ' , 'region_id' => 2],
            ['name_arabic' => ' السر ' , 'name_english' => ' ALSER ' , 'region_id' => 2],
            ['name_arabic' => ' عشيرة ' , 'name_english' => ' ASHERAH ' , 'region_id' => 2],
            ['name_arabic' => ' الصويدرة ' , 'name_english' => ' ALSOWAIDRAH ' , 'region_id' => 3],
            ['name_arabic' => ' ﺍﻟﺮايس ' , 'name_english' => ' AL-RAIS ' , 'region_id' => 3],
            ['name_arabic' => ' وادي الفرع ' , 'name_english' => ' WADI AL-FREI ' , 'region_id' => 3],
            ['name_arabic' => ' ﻋﻘﻠﺔ ﺍﻟﺼﻘﻮﺭ ' , 'name_english' => ' AKLAT AL-SKOUR ' , 'region_id' => 4],
            ['name_arabic' => ' ضربة ' , 'name_english' => ' DHERIAH ' , 'region_id' => 4],
            ['name_arabic' => ' القصيباء ' , 'name_english' => ' AL-KOSYBAA ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻟﺸﻨﺎﻧﺔ ' , 'name_english' => ' AL-SHANANA ' , 'region_id' => 4],
            ['name_arabic' => ' البتراء ' , 'name_english' => ' AL-BATRAA ' , 'region_id' => 4],
            ['name_arabic' => ' قبة ' , 'name_english' => ' KBAH ' , 'region_id' => 4],
            ['name_arabic' => ' صبيح ' , 'name_english' => ' SABEH ' , 'region_id' => 4],
            ['name_arabic' => ' الشبيكية ' , 'name_english' => ' AL-SHBEKEAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻟﻤﻠﻘﺎﺀ ' , 'name_english' => ' AL-MALQA ' , 'region_id' => 4],
            ['name_arabic' => ' ﺩﺧﻨﻪ ' , 'name_english' => ' DKNAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﻋﻄﺎﺀ ' , 'name_english' => ' ATAA ' , 'region_id' => 4],
            ['name_arabic' => ' ابانات ' , 'name_english' => ' ABANAT ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻟﻘﻮﺍﺭﻩ ' , 'name_english' => ' AL-QWARAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍبا ﺍﻟﻮﺭﻭﺩ ' , 'name_english' => ' ABA ALWROD ' , 'region_id' => 4],
            ['name_arabic' => ' الظاهرية ' , 'name_english' => ' AL-DAHREAH ' , 'region_id' => 4],
            ['name_arabic' => ' الدليمية ' , 'name_english' => ' AL-DLEMIEAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻟﺼﻤﻌﻮرية ' , 'name_english' => ' AL-SAMOREAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻟﻌﻤﺎﺭ ' , 'name_english' => ' AL-AMAR ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍلبطين ' , 'name_english' => ' AL-BTEEN ' , 'region_id' => 4],
            ['name_arabic' => ' شري ' , 'name_english' => ' SHARI ' , 'region_id' => 4],
            ['name_arabic' => ' ﻣﺪﺭﺝ ' , 'name_english' => ' MADRAG ' , 'region_id' => 4],
            ['name_arabic' => ' الذيبية ' , 'name_english' => ' AL-DEBIAH ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍلقرين ' , 'name_english' => ' AL-QREEN ' , 'region_id' => 4],
            ['name_arabic' => ' ﺍﻟﻬﻔﻮﻑ ' , 'name_english' => ' ALHAFOUF ' , 'region_id' => 5],
            ['name_arabic' => ' ﻣﻨﻔﺬ ﺍﻟﺮقعي ' , 'name_english' => ' AL-RAKEE ' , 'region_id' => 5],
            ['name_arabic' => ' جسر ﺍﻟﻤﻠﻚ ﻓﻬﺪ ' , 'name_english' => ' KING FAHAD CAUSEWAY ' , 'region_id' => 5],
            ['name_arabic' => ' ﺳﻠﻮﻯ ' , 'name_english' => ' SALWA ' , 'region_id' => 5],
            ['name_arabic' => ' ﺍﻟﻌﺪيد ' , 'name_english' => ' ALADEED ' , 'region_id' => 5],
            ['name_arabic' => ' بطحاء ' , 'name_english' => ' BATHA ' , 'region_id' => 5],
            ['name_arabic' => ' ﺗﺎﺭﻭﺕ ' , 'name_english' => ' TAROUT ' , 'region_id' => 5],
            ['name_arabic' => ' ﺍﻟبرك ' , 'name_english' => ' ALBRK ' , 'region_id' => 6],
            ['name_arabic' => ' ﺳبت ﺍﻟﻌﻼ ية ' , 'name_english' => ' SABT AL-ALAYAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﻋﻠﺐ ' , 'name_english' => ' ALB ' , 'region_id' => 6],
            ['name_arabic' => ' الحريضة ' , 'name_english' => ' AL-HREDAH ' , 'region_id' => 6],
            ['name_arabic' => ' باللسمر ' , 'name_english' => ' BLSAMAR ' , 'region_id' => 6],
            ['name_arabic' => ' ﺎﻟﻠﺤﻤﺮ ' , 'name_english' => ' BLHAMR ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻟﺠﻮﺓ ' , 'name_english' => ' ALJWA ' , 'region_id' => 6],
            ['name_arabic' => ' المسقي ' , 'name_english' => ' AL-MASKA ' , 'region_id' => 6],
            ['name_arabic' => ' ﻗﻨﺎﺀ و البحر ' , 'name_english' => ' KNAA WLBAHR ' , 'region_id' => 6],
            ['name_arabic' => ' ﻭﺍﺩﻱ هشبل ' , 'name_english' => ' WADY HSHBL ' , 'region_id' => 6],
            ['name_arabic' => ' بني ﻋﻤﺮﻭ ' , 'name_english' => ' BANY AMR ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍلبشاير ' , 'name_english' => ' AL-BASHAYER ' , 'region_id' => 6],
            ['name_arabic' => ' ﺗﻨﻮﻣﺔ ' , 'name_english' => ' TNOMAH ' , 'region_id' => 6],
            ['name_arabic' => ' بارق ' , 'name_english' => ' BAREK ' , 'region_id' => 6],
            ['name_arabic' => ' الفطيحة ' , 'name_english' => ' AL-FTEHAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻟﺸﻌﻒ ' , 'name_english' => ' AL-SHAAF ' , 'region_id' => 6],
            ['name_arabic' => ' طريب ' , 'name_english' => ' TAREEB ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻟﺨﻨﻘﺔ ' , 'name_english' => ' AL-KANQAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻟﺨﺜﻌﻢ ' , 'name_english' => ' AL-KTHAM ' , 'region_id' => 6],
            ['name_arabic' => ' السرح ' , 'name_english' => ' AL-SARH ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻟﺤﺮﺟﺔ ' , 'name_english' => ' AL-HARGAH ' , 'region_id' => 6],
            ['name_arabic' => ' مرية ' , 'name_english' => ' MAREAH ' , 'region_id' => 6],
            ['name_arabic' => ' باشوت ' , 'name_english' => ' BASHOOT ' , 'region_id' => 6],
            ['name_arabic' => ' بحر ابو سكينه ' , 'name_english' => ' BAHAR ABU SAKENAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﻋﻔﺮﺍﺀ ' , 'name_english' => ' AFRA ' , 'region_id' => 6],
            ['name_arabic' => ' تهامة باللحمر وباللسمر ' , 'name_english' => ' TOHAMAH BALHMR WEBALSAMER ' , 'region_id' => 6],
            ['name_arabic' => ' العرين ' , 'name_english' => ' AL-AREEN ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻟﻤﻀﻪ ' , 'name_english' => ' AL-MADAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻟﻘﺤﻤﺔ ' , 'name_english' => ' AL-QHMAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﻣﺪﻳﻨﺔ ﺳﻠﻄﺎﻥ ' , 'name_english' => ' SULTAN CITY ' , 'region_id' => 6],
            ['name_arabic' => ' ﺭﻏﻮﻩ ' , 'name_english' => ' RAQWAH ' , 'region_id' => 6],
            ['name_arabic' => ' ﺍﻻﻣﻮﺍﻩ ' , 'name_english' => ' AL-AMWAH ' , 'region_id' => 6],
            ['name_arabic' => ' جبه ' , 'name_english' => ' JUBAH ' , 'region_id' => 7],
            ['name_arabic' => ' ﺍﻟﺨﻄﺔ ' , 'name_english' => ' ALKHTAH ' , 'region_id' => 7],
            ['name_arabic' => ' ﺍﻟﺸملي ' , 'name_english' => ' AL-SHMLY ' , 'region_id' => 7],
            ['name_arabic' => ' الحليفة ' , 'name_english' => ' AL-HLEFEAH ' , 'region_id' => 7],
            ['name_arabic' => ' ﻣﻮﻗﻖ ' , 'name_english' => ' MOQAQ ' , 'region_id' => 7],
            ['name_arabic' => ' ﺍﻟﺪﺭﻩ ' , 'name_english' => ' ALDORRA ' , 'region_id' => 8],
            ['name_arabic' => ' ﺣﺎﻟﺔ ﻋﻤﺎﺭ ' , 'name_english' => ' HALUT AMMAR ' , 'region_id' => 8],
            ['name_arabic' => ' بئرين ﻫﺮﻣﺎﺱ ' , 'name_english' => ' BEER BN HRMAS ' , 'region_id' => 8],
            ['name_arabic' => ' ﺍﻟﺠﻬﺮﺍﺀ ' , 'name_english' => ' AL-GAHRA ' , 'region_id' => 8],
            ['name_arabic' => ' شرما ' , 'name_english' => ' SHRMA ' , 'region_id' => 8],
            ['name_arabic' => ' ﺷﻘﺮﻱ ' , 'name_english' => ' SHAQRE ' , 'region_id' => 8],
            ['name_arabic' => ' ﻭﺭﺍﺥ ' , 'name_english' => ' WRAQ ' , 'region_id' => 9],
            ['name_arabic' => ' ﺟﺮﺏ ' , 'name_english' => ' GRB ' , 'region_id' => 9],
            ['name_arabic' => ' جديدة عرعر ' , 'name_english' => ' JADEEDAT ARAR ' , 'region_id' => 10],
            ['name_arabic' => ' ﺍﻟﺸﻌ ﺔ ' , 'name_english' => ' AL-SHAABAH ' , 'region_id' => 10],
            ['name_arabic' => ' ﺣﺰﻡ الجلاميد ' , 'name_english' => ' HZM AL-GLAMEED ' , 'region_id' => 10],
            ['name_arabic' => ' ﻗ ﺼﻮﻣﺔ ﻓ ﺤﺎﻥ ' , 'name_english' => ' QESOMAT FEHAN ' , 'region_id' => 10],
            ['name_arabic' => ' ﺍﻟﻤﺮﻛﻮﺯ ' , 'name_english' => ' AL-MRKOZ ' , 'region_id' => 10],
            ['name_arabic' => ' ﻟﻴﻨﻪ ' , 'name_english' => ' LENAH ' , 'region_id' => 10],
            ['name_arabic' => ' ﺍﻟﻨﺼﺎﺏ ' , 'name_english' => ' AL-NSAB ' , 'region_id' => 10],
            ['name_arabic' => ' ﺍﻟﺪﻭ ﺮ ' , 'name_english' => ' AL-DWER ' , 'region_id' => 10],
            ['name_arabic' => ' ﺍﻟﺤﺪﻳﺜﻪ ' , 'name_english' => ' ALHADEETHA ' , 'region_id' => 11],
            ['name_arabic' => ' ﻃﻠﻌﺔ ﻋﻤﺎﺭ ' , 'name_english' => ' TALLAT AMAR ' , 'region_id' => 11],
            ['name_arabic' => ' ﻣﻨﻔﺬ ﺍﻟﻄﻮﺍﻝ ' , 'name_english' => ' ALTWAL ' , 'region_id' => 12],
            ['name_arabic' => ' ﺍﻟﻤﻮﺳﻢ ' , 'name_english' => ' ALMOWASSAM ' , 'region_id' => 12],
            ['name_arabic' => ' عيبان ' , 'name_english' => ' EIBAN ' , 'region_id' => 12],
            ['name_arabic' => ' الخوبة ' , 'name_english' => ' AL-KOUBAH ' , 'region_id' => 12],
            ['name_arabic' => ' فيفاء ' , 'name_english' => ' FIFAA ' , 'region_id' => 12],
            ['name_arabic' => ' المضايا ' , 'name_english' => ' AL-MADAYA ' , 'region_id' => 12],
            ['name_arabic' => ' ﺍلريان بجازان ' , 'name_english' => ' AL-RAYAN BJAZAN ' , 'region_id' => 12],
            ['name_arabic' => ' ﺍﻟشقيري ' , 'name_english' => ' AL-SHKERY ' , 'region_id' => 12],
            ['name_arabic' => ' ﻣﻨﻔﺬ الخضراء ' , 'name_english' => ' ALKHADHRA ' , 'region_id' => 13],
            ['name_arabic' => ' ﻣﻨﻔﺬ الوديعة ' , 'name_english' => ' AL-WADEAAH ' , 'region_id' => 13],
            ['name_arabic' => ' العريسة ' , 'name_english' => ' AL-AREESAH ' , 'region_id' => 13],
            ['name_arabic' => ' ﺍﻟﺤصينية ' , 'name_english' => ' AL-HSENYAH ' , 'region_id' => 13],
        ];

        DB::table('cities')->insert($cities);
    }
}
