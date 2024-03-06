<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GoodsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $goodstypes = [
            ['name_arabic' => ' ﺳﺎﺋﻠﺔ ', 'name_english' => 'Liquid' ,'parent_id' => null ],
            ['name_arabic' => ' ﺳﺎﺋﺒﺔ ', 'name_english' => 'Bulk' ,'parent_id' => null ],
            ['name_arabic' => ' ﻣﺒﺮﺩﺓ ', 'name_english' => 'Refrigerated' ,'parent_id' => null ],
            ['name_arabic' => ' ﺯﺭاﻋﻴﺔ ', 'name_english' => 'Agricultural' ,'parent_id' => null ],
            ['name_arabic' => ' ﺻﻨﺎﻋﻴﺔ ', 'name_english' => 'Industrial' ,'parent_id' => null ],
            ['name_arabic' => ' ﺗﻌدﻳﻨﻴﺔ ', 'name_english' => 'Mining' ,'parent_id' => null ],
            ['name_arabic' => ' ﻣﻌداﺕ ', 'name_english' => 'Equipment' ,'parent_id' => null ],
            ['name_arabic' => ' ﺁﻻﺕ ', 'name_english' => 'Machines' ,'parent_id' => null ],
            ['name_arabic' => ' ﺳﻴﺎﺭاﺕ ', 'name_english' => 'Cars' ,'parent_id' => null ],
            ['name_arabic' => ' ﻣﻨﺘﺠﺎﺕ ﺑﺘﺮﻭﻟﻴﺔ ', 'name_english' => 'Petroleum Products' ,'parent_id' => null ],
            ['name_arabic' => ' ﺣﻴﻮاﻧﺎﺕ ﺣﻴﺔ ', 'name_english' => 'LIVE ANIMALS' ,'parent_id' => null ],
            ['name_arabic' => ' ﻣﻮاﺩ ﻏذاﺋﻴﺔ ', 'name_english' => 'Food Items' ,'parent_id' => null ],
            ['name_arabic' => ' ﻧﻘﻞ اﻟﻤﺮﻛﺒﺎﺕ ', 'name_english' => 'Vehicle Transportation' ,'parent_id' => null ],
            ['name_arabic' => ' ﺳﻠﻊ اﺳﺘﻬﻼﻛﻴﺔ ﺳﺮﻳﻌﺔ اﻟﺤﺮﻛﺔ اﻟﺠﺎﻓﺔ ', 'name_english' => 'Dry Fast-Moving Consum' , 'parent_id' => 12],
            ['name_arabic' => ' ﺳﻠﻊ اﺳﺘﻬﻼﻛﻴﺔ ﺳﺮﻳﻌﺔ اﻟﺤﺮﻛﺔ اﻟﻤﺒﺮﺩﺓ ', 'name_english' => 'Cold Fast-Moving Consum' , 'parent_id' => 12],
            ['name_arabic' => ' ﻣﻮاﺩ اﻟﺮﻋﺎﻳﺔ اﻟﺼﺤﻴﺔ اﻟﺠﺎﻓﺔ ', 'name_english' => 'Dry Health Care' , 'parent_id' => 12],
            ['name_arabic' => ' ﻣﻮاﺩ اﻟﺮﻋﺎﻳﺔ اﻟﺼﺤﻴﺔ اﻟﻤﺒﺮﺩﺓ ', 'name_english' => 'Cold Health Care' , 'parent_id' => 12],
            ['name_arabic' => ' ﺑﻨﺰﻳﻦ 91', 'name_english' => 'Petrol 91' , 'parent_id' => 10],
            ['name_arabic' => ' ﺑﻨﺰﻳﻦ 95', 'name_english' => 'Petrol 95' , 'parent_id' => 10],
            ['name_arabic' => ' ﺩﻳﺰﻝ ', 'name_english' => 'Diesel' , 'parent_id' => 10],
            ['name_arabic' => ' ﻛﻴﺮﻭﺳﻴﻦ ', 'name_english' => 'Kerosene' , 'parent_id' => 10],
            ['name_arabic' => ' ﺑﺘﺮﻭﻛﻴﻤﺎﻭﻳﺎﺕ ', 'name_english' => 'Petrochemicals' , 'parent_id' => 10],
            ['name_arabic' => ' ﺯﻳﻮﺕ ', 'name_english' => 'Oils' , 'parent_id' => 10],
            ['name_arabic' => ' ﻏﺎﺯ ﻧﻔﻄﻲ ', 'name_english' => 'Petroleum Gas' , 'parent_id' => 10],
            ['name_arabic' => ' ﻗﺎﺭ ', 'name_english' => 'Continent' , 'parent_id' => 10],
            ['name_arabic' => ' ﺷﻤﻊ ﺑﺮاﻓﻴﻦ ', 'name_english' => 'Paraffin Wax' , 'parent_id' => 10],
            ['name_arabic' => ' اﺳﻔﻠﺖ ', 'name_english' => 'Asphalt' , 'parent_id' => 10],
            ['name_arabic' => ' ﻭﻗﻮﺩ اﻟﻄﺎﺋﺮاﺕ ', 'name_english' => 'Jet Fuel' , 'parent_id' => 10],
            ['name_arabic' => ' اﻟﻨﻘﻞ اﻟﺨﺎﺹ ﻟﻼﻓﺮاﺩ ', 'name_english' => 'Private Transportation For Individuals' , 'parent_id' => 13],
            ['name_arabic' => ' ﻧﻘﻞ اﻟﻤﺮﻛﺒﺎﺕ ﻟﻼﻓﺮاﺩ ', 'name_english' => 'Vehicle Transportation For Individuals' , 'parent_id' => 13],
            ['name_arabic' => ' اﻟﻨﻘﻞ اﻟﺨﺎﺹ ﻟﻘﻄﺎﻉ اﻻﻋﻤﺎﻝ ', 'name_english' => 'Private Transport For Business Sector' , 'parent_id' => 13],
            ['name_arabic' => ' ﻧﻘﻞ اﻟﻤﺮﻛﺒﺎﺕ ﻟﻘﻄﺎﻉ اﻻﻋﻤﺎﻝ ', 'name_english' => 'Business Vehicle Transportation' , 'parent_id' => 13],
            ['name_arabic' => ' ﻧﻘﻞ اﻟﻤﺮﻛﺒﺎﺕ اﻟﻤﺘﻀﺮﺭﺓ ', 'name_english' => 'Damaged Vehicles Transportation' , 'parent_id' => 13],
            ['name_arabic' => ' ﻣﻮاﺩ اﻟﺒﻨﺎء ﻭ اﻟﺘﺸﻴﻴد ', 'name_english' => 'Building And Construction Items' ,'parent_id' => null ],
            ['name_arabic' => ' ﻣﻮاﺩ ﺧﻄﺮﺓ ', 'name_english' => 'Dangerous Materials' ,'parent_id' => null ],
            ['name_arabic' => ' اﻟﻤﺘﻔﺠﺮاﺕ ', 'name_english' => 'Explosives' , 'parent_id' => 35],
            ['name_arabic' => ' اﻟﻐﺎﺯاﺕ ', 'name_english' => 'Gases' , 'parent_id' => 35],
            ['name_arabic' => ' اﻟﺴﻮاﺋﻞ اﻟﻠﻬﻮﺑﺔ ', 'name_english' => 'Flammable Liquid' , 'parent_id' => 35],
            ['name_arabic' => ' اﻟﻤﻮاﺩ اﻟﺼﻠﺒﺔ اﻟﻠﻬﻮﺑﺔ ', 'name_english' => 'Flammable Solids' , 'parent_id' => 35],
            ['name_arabic' => ' اﻟﻤﻮاﺩ اﻟﻤﺆﻛﺴدﺓ ﻭاﻷﻛﺎﺳﻴد اﻟﻔﻮﻗﻴﺔ اﻟﻌﻀﻮﻳﺔ ', 'name_english' => 'Oxidizers and organic peroxides' , 'parent_id' => 35],
            ['name_arabic' => ' اﻟﻤﻮاﺩ اﻟﺴﻤﻴﺔ ﻭاﻟﻤﻮاﺩ اﻟﻤﻌدﻳﺔ ', 'name_english' => 'Toxic and infectious substances' , 'parent_id' => 35],
            ['name_arabic' => ' اﻟﻤﻮاﺩ اﻟﻤﺸﻌﺔ ', 'name_english' => 'Radioactive material' , 'parent_id' => 35],
            ['name_arabic' => ' اﻟﻤﻮاﺩ اﻷﻛﺎﻟﺔ ', 'name_english' => 'Corrosive substances' , 'parent_id' => 35],
            ['name_arabic' => ' ﻣﻮاﺩ ﻭﺳﻠﻊ ﺧﻄﺮﺓ ﻣﺘﻨﻮﻋﺔ، ﺑﻤﺎ ﻓﻲ ﺫﻟﻚ اﻟﻤﻮاﺩ اﻟﺨﻄﺮﺓ ﺑﻴﺌﻴﺎً ', 'name_english' => 'Miscellaneous dangerous substances' , 'parent_id' => 35],
            ['name_arabic' => ' اﻟﺨﺮﺳﺎﻧﺔ ', 'name_english' => 'Concrete' , 'parent_id' => 34],
            ['name_arabic' => ' اﻟﺼﻠﺐ ', 'name_english' => 'Steel' , 'parent_id' => 34],
            ['name_arabic' => ' اﻷﻧﺎﺑﻴﺐ ﻭﻣﻮاﺩ ﺗﻤدﻳد اﻷﻋﻤﺎﻝ اﻟﻤﺎﺋﻴﺔ ﻭﺗﺸﻤﻞ اﻟﺨﺰاﻧﺎﺕ ', 'name_english' => 'Pipes and water extension materials include tanks' , 'parent_id' => 34],
            ['name_arabic' => ' اﻷﻟﻮاﺡ اﻟﺰﺟﺎﺟﻴﺔ ﻭاﻷﻟﻤﻨﻴﻮﻡ ﻭﺗﺸﻤﻞ اﻷﺑﻮاﺏ ﻭاﻟﻨﻮاﻓذ ', 'name_english' => 'Glass and aluminum panels include doors and windows' , 'parent_id' => 34],
            ['name_arabic' => ' اﻋﻤﺎﻝ اﻟﺘﺸﻄﻴﺐ ﻭاﻟدﻫﺎﻧﺎﺕ ﻭاﻟﺘﻜﺴﻴﺎﺕ اﻟﺨﺎﺭﺟﻴﺔ ', 'name_english' => 'Finishing works, paints and external textures' , 'parent_id' => 34],
            ['name_arabic' => ' اﻟﻤﻌداﺕ ﻭاﻷﺩﻭاﺕ ﻭﺗﺸﻤﻞ اﻟﻤﻨﺎﺷﻴﺮ ﻭاﻟﻤﻄﺎﺭﻕ ﻭﻣﺮاﻭﺡ اﻟﻠﻴﺎﺳﺔ ﻭﻫﺰاﺯاﺕ اﻟﺨﺮﺳﺎﻧﺔ ', 'name_english' => 'Equipment and tools include saws, hammers, fisher' , 'parent_id' => 34],
            ['name_arabic' => ' ﺃﻋﻤﺎﻝ اﻟﺴﺒﺎﻛﺔ ﻭاﻟﺘﻤدﻳداﺕ اﻟﺼﺤﻴﺔ ', 'name_english' => 'Plumbing work and health extensions' , 'parent_id' => 34],
            ['name_arabic' => ' ﻣﻮاﺩ اﻟﻌﺰﻝ ', 'name_english' => 'Insulating materials' , 'parent_id' => 34],
            ['name_arabic' => ' ﻣﻮاﺩ اﻟدﻓﺎﻥ ﻭاﻟﺴﻔﻠﺘﺔ ', 'name_english' => 'Book and asphalt materials' , 'parent_id' => 34],
            ['name_arabic' => ' اﻷﺧﺸﺎﺏ ﻭﺗﺸﻤﻞ ﺃﺧﺸﺎﺏ اﻟﻨﺠﺎﺭﺓ ﻭاﻷﺑﻮاﺏ ﻭﺧﻼﻓﺔ ', 'name_english' => 'Woods include carpentry wood, doors and succession' , 'parent_id' => 34],
            ['name_arabic' => ' ﺣدﻳد اﻟﺘﺴﻠﻴﺢ ', 'name_english' => 'Reinforcing' , 'parent_id' => 34],
            ['name_arabic' => ' ﻣﻮاﺩ ﺗﻤدﻳداﺕ اﻟﻜﻬﺮﺑﺎء ', 'name_english' => 'Electricity extensions materials' , 'parent_id' => 34],
            ['name_arabic' => ' اﻷﺣﺠﺎﺭ ﻭاﻟﺼﺨﻮﺭ ﻭﻣﻮاﺩ اﻟﺮﺻﻒ ﻭﻳﺸﻜﻞ ﺫﻟﻚ اﻟﺒﻼﻁ ', 'name_english' => 'Stones, rocks and paving materials, and this is formed tiles' , 'parent_id' => 34],
        ];

        DB::table('goods_types')->insert($goodstypes);
    }
}
