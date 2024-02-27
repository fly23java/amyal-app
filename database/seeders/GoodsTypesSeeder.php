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
            ['name_arabic' => ' ﺯﺭﺍﻋﻴﺔ ', 'name_english' => 'Agricultural' ,'parent_id' => null ],
            ['name_arabic' => ' ﺻﻨﺎﻋﻴﺔ ', 'name_english' => 'Industrial' ,'parent_id' => null ],
            ['name_arabic' => ' ﺗﻌﺪﻳﻨﻴﺔ ', 'name_english' => 'Mining' ,'parent_id' => null ],
            ['name_arabic' => ' ﻣﻌﺪﺍﺕ ', 'name_english' => 'Equipment' ,'parent_id' => null ],
            ['name_arabic' => ' ﺁﻻﺕ ', 'name_english' => 'Machines' ,'parent_id' => null ],
            ['name_arabic' => ' ﺳﻴﺎﺭﺍﺕ ', 'name_english' => 'Cars' ,'parent_id' => null ],
            ['name_arabic' => ' ﻣﻨﺘﺠﺎﺕ ﺑﺘﺮﻭﻟﻴﺔ ', 'name_english' => 'Petroleum Products' ,'parent_id' => null ],
            ['name_arabic' => ' ﺣﻴﻮﺍﻧﺎﺕ ﺣﻴﺔ ', 'name_english' => 'LIVE ANIMALS' ,'parent_id' => null ],
            ['name_arabic' => ' ﻣﻮﺍﺩ ﻏﺬﺍﺋﻴﺔ ', 'name_english' => 'Food Items' ,'parent_id' => null ],
            ['name_arabic' => ' ﻧﻘﻞ ﺍﻟﻤﺮﻛﺒﺎﺕ ', 'name_english' => 'Vehicle Transportation' ,'parent_id' => null ],
            ['name_arabic' => ' ﺳﻠﻊ ﺍﺳﺘﻬﻼﻛﻴﺔ ﺳﺮﻳﻌﺔ ﺍﻟﺤﺮﻛﺔ ﺍﻟﺠﺎﻓﺔ ', 'name_english' => 'Dry Fast-Moving Consum' , 'parent_id' => 12],
            ['name_arabic' => ' ﺳﻠﻊ ﺍﺳﺘﻬﻼﻛﻴﺔ ﺳﺮﻳﻌﺔ ﺍﻟﺤﺮﻛﺔ ﺍﻟﻤﺒﺮﺩﺓ ', 'name_english' => 'Cold Fast-Moving Consum' , 'parent_id' => 12],
            ['name_arabic' => ' ﻣﻮﺍﺩ ﺍﻟﺮﻋﺎﻳﺔ ﺍﻟﺼﺤﻴﺔ ﺍﻟﺠﺎﻓﺔ ', 'name_english' => 'Dry Health Care' , 'parent_id' => 12],
            ['name_arabic' => ' ﻣﻮﺍﺩ ﺍﻟﺮﻋﺎﻳﺔ ﺍﻟﺼﺤﻴﺔ ﺍﻟﻤﺒﺮﺩﺓ ', 'name_english' => 'Cold Health Care' , 'parent_id' => 12],
            ['name_arabic' => ' ﺑﻨﺰﻳﻦ 91', 'name_english' => 'Petrol 91' , 'parent_id' => 10],
            ['name_arabic' => ' ﺑﻨﺰﻳﻦ 95', 'name_english' => 'Petrol 95' , 'parent_id' => 10],
            ['name_arabic' => ' ﺩﻳﺰﻝ ', 'name_english' => 'Diesel' , 'parent_id' => 10],
            ['name_arabic' => ' ﻛﻴﺮﻭﺳﻴﻦ ', 'name_english' => 'Kerosene' , 'parent_id' => 10],
            ['name_arabic' => ' ﺑﺘﺮﻭﻛﻴﻤﺎﻭﻳﺎﺕ ', 'name_english' => 'Petrochemicals' , 'parent_id' => 10],
            ['name_arabic' => ' ﺯﻳﻮﺕ ', 'name_english' => 'Oils' , 'parent_id' => 10],
            ['name_arabic' => ' ﻏﺎﺯ ﻧﻔﻄﻲ ', 'name_english' => 'Petroleum Gas' , 'parent_id' => 10],
            ['name_arabic' => ' ﻗﺎﺭ ', 'name_english' => 'Continent' , 'parent_id' => 10],
            ['name_arabic' => ' ﺷﻤﻊ ﺑﺮﺍﻓﻴﻦ ', 'name_english' => 'Paraffin Wax' , 'parent_id' => 10],
            ['name_arabic' => ' ﺍﺳﻔﻠﺖ ', 'name_english' => 'Asphalt' , 'parent_id' => 10],
            ['name_arabic' => ' ﻭﻗﻮﺩ ﺍﻟﻄﺎﺋﺮﺍﺕ ', 'name_english' => 'Jet Fuel' , 'parent_id' => 10],
            ['name_arabic' => ' ﺍﻟﻨﻘﻞ ﺍﻟﺨﺎﺹ ﻟﻼﻓﺮﺍﺩ ', 'name_english' => 'Private Transportation For Individuals' , 'parent_id' => 13],
            ['name_arabic' => ' ﻧﻘﻞ ﺍﻟﻤﺮﻛﺒﺎﺕ ﻟﻼﻓﺮﺍﺩ ', 'name_english' => 'Vehicle Transportation For Individuals' , 'parent_id' => 13],
            ['name_arabic' => ' ﺍﻟﻨﻘﻞ ﺍﻟﺨﺎﺹ ﻟﻘﻄﺎﻉ ﺍﻻﻋﻤﺎﻝ ', 'name_english' => 'Private Transport For Business Sector' , 'parent_id' => 13],
            ['name_arabic' => ' ﻧﻘﻞ ﺍﻟﻤﺮﻛﺒﺎﺕ ﻟﻘﻄﺎﻉ ﺍﻻﻋﻤﺎﻝ ', 'name_english' => 'Business Vehicle Transportation' , 'parent_id' => 13],
            ['name_arabic' => ' ﻧﻘﻞ ﺍﻟﻤﺮﻛﺒﺎﺕ ﺍﻟﻤﺘﻀﺮﺭﺓ ', 'name_english' => 'Damaged Vehicles Transportation' , 'parent_id' => 13],
            ['name_arabic' => ' ﻣﻮﺍﺩ ﺍﻟﺒﻨﺎء ﻭ ﺍﻟﺘﺸﻴﻴﺪ ', 'name_english' => 'Building And Construction Items' ,'parent_id' => null ],
            ['name_arabic' => ' ﻣﻮﺍﺩ ﺧﻄﺮﺓ ', 'name_english' => 'Dangerous Materials' ,'parent_id' => null ],
            ['name_arabic' => ' ﺍﻟﻤﺘﻔﺠﺮﺍﺕ ', 'name_english' => 'Explosives' , 'parent_id' => 35],
            ['name_arabic' => ' ﺍﻟﻐﺎﺯﺍﺕ ', 'name_english' => 'Gases' , 'parent_id' => 35],
            ['name_arabic' => ' ﺍﻟﺴﻮﺍﺋﻞ ﺍﻟﻠﻬﻮﺑﺔ ', 'name_english' => 'Flammable Liquid' , 'parent_id' => 35],
            ['name_arabic' => ' ﺍﻟﻤﻮﺍﺩ ﺍﻟﺼﻠﺒﺔ ﺍﻟﻠﻬﻮﺑﺔ ', 'name_english' => 'Flammable Solids' , 'parent_id' => 35],
            ['name_arabic' => ' ﺍﻟﻤﻮﺍﺩ ﺍﻟﻤﺆﻛ ِﺴﺪﺓ ﻭﺍﻷﻛﺎﺳﻴﺪ ﺍﻟﻔﻮﻗﻴﺔ ﺍﻟﻌﻀﻮﻳﺔ ', 'name_english' => 'Oxidizers and organic peroxides' , 'parent_id' => 35],
            ['name_arabic' => ' ﺍﻟﻤﻮﺍﺩ ﺍﻟﺴﻤﻴﺔ ﻭﺍﻟﻤﻮﺍﺩ ﺍﻟﻤﻌﺪﻳﺔ ', 'name_english' => 'Toxic and infectious substances' , 'parent_id' => 35],
            ['name_arabic' => ' ﺍﻟﻤﻮﺍﺩ ﺍﻟﻤﺸﻌﺔ ', 'name_english' => 'Radioactive material' , 'parent_id' => 35],
            ['name_arabic' => ' ﺍﻟﻤﻮﺍﺩ ﺍﻷﻛﺎﻟﺔ ', 'name_english' => 'Corrosive substances' , 'parent_id' => 35],
            ['name_arabic' => ' ﻣﻮﺍﺩ ﻭﺳﻠﻊ ﺧﻄﺮﺓ ﻣﺘﻨﻮﻋﺔ، ﺑﻤﺎ ﻓﻲ ﺫﻟﻚ ﺍﻟﻤﻮﺍﺩ ﺍﻟﺨﻄﺮﺓ ﺑﻴﺌﻴﺎً ', 'name_english' => 'Miscellaneous dangerous substances' , 'parent_id' => 35],
            ['name_arabic' => ' ﺍﻟﺨﺮﺳﺎﻧﺔ ', 'name_english' => 'Concrete' , 'parent_id' => 34],
            ['name_arabic' => ' ﺍﻟﺼﻠﺐ ', 'name_english' => 'Steel' , 'parent_id' => 34],
            ['name_arabic' => ' ﺍﻷﻧﺎﺑﻴﺐ ﻭﻣﻮﺍﺩ ﺗﻤﺪﻳﺪ ﺍﻷﻋﻤﺎﻝ ﺍﻟﻤﺎﺋﻴﺔ ﻭﺗﺸﻤﻞ ﺍﻟﺨﺰﺍﻧﺎﺕ ', 'name_english' => 'Pipes and water extension materials include tanks' , 'parent_id' => 34],
            ['name_arabic' => ' ﺍﻷﻟﻮﺍﺡ ﺍﻟﺰﺟﺎﺟﻴﺔ ﻭﺍﻷﻟﻤﻨﻴﻮﻡ ﻭﺗﺸﻤﻞ ﺍﻷﺑﻮﺍﺏ ﻭﺍﻟﻨﻮﺍﻓﺬ ', 'name_english' => 'Glass and aluminum panels include doors and windows' , 'parent_id' => 34],
            ['name_arabic' => ' ﺍﻋﻤﺎﻝ ﺍﻟﺘﺸﻄﻴﺐ ﻭﺍﻟﺪﻫﺎﻧﺎﺕ ﻭﺍﻟﺘﻜﺴﻴﺎﺕ ﺍﻟﺨﺎﺭﺟﻴﺔ ', 'name_english' => 'Finishing works, paints and external textures' , 'parent_id' => 34],
            ['name_arabic' => ' ﺍﻟﻤﻌﺪﺍﺕ ﻭﺍﻷﺩﻭﺍﺕ ﻭﺗﺸﻤﻞ ﺍﻟﻤﻨﺎﺷﻴﺮ ﻭﺍﻟﻤﻄﺎﺭﻕ ﻭﻣﺮﺍﻭﺡ ﺍﻟﻠﻴﺎﺳﺔ ﻭﻫﺰﺍﺯﺍﺕ ﺍﻟﺨﺮﺳﺎﻧﺔ ', 'name_english' => 'Equipment and tools include saws, hammers, fisher' , 'parent_id' => 34],
            ['name_arabic' => ' ﺃﻋﻤﺎﻝ ﺍﻟﺴﺒﺎﻛﺔ ﻭﺍﻟﺘﻤﺪﻳﺪﺍﺕ ﺍﻟﺼﺤﻴﺔ ', 'name_english' => 'Plumbing work and health extensions' , 'parent_id' => 34],
            ['name_arabic' => ' ﻣﻮﺍﺩ ﺍﻟﻌﺰﻝ ', 'name_english' => 'Insulating materials' , 'parent_id' => 34],
            ['name_arabic' => ' ﻣﻮﺍﺩ ﺍﻟﺪﻓﺎﻥ ﻭﺍﻟﺴﻔﻠﺘﺔ ', 'name_english' => 'Book and asphalt materials' , 'parent_id' => 34],
            ['name_arabic' => ' ﺍﻷﺧﺸﺎﺏ ﻭﺗﺸﻤﻞ ﺃﺧﺸﺎﺏ ﺍﻟﻨﺠﺎﺭﺓ ﻭﺍﻷﺑﻮﺍﺏ ﻭﺧﻼﻓﺔ ', 'name_english' => 'Woods include carpentry wood, doors and succession' , 'parent_id' => 34],
            ['name_arabic' => ' ﺣﺪﻳﺪ ﺍﻟﺘﺴﻠﻴﺢ ', 'name_english' => 'Reinforcing' , 'parent_id' => 34],
            ['name_arabic' => ' ﻣﻮﺍﺩ ﺗﻤﺪﻳﺪﺍﺕ ﺍﻟﻜﻬﺮﺑﺎء ', 'name_english' => 'Electricity extensions materials' , 'parent_id' => 34],
            ['name_arabic' => ' ﺍﻷﺣﺠﺎﺭ ﻭﺍﻟﺼﺨﻮﺭ ﻭﻣﻮﺍﺩ ﺍﻟﺮﺻﻒ ﻭﻳﺸﻜﻞ ﺫﻟﻚ ﺍﻟﺒﻼﻁ ', 'name_english' => 'Stones, rocks and paving materials, and this is formed tiles' , 'parent_id' => 34],
        ];

        DB::table('goods_types')->insert($goodstypes);
    }
}
