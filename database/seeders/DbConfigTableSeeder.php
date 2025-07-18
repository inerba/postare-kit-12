<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DbConfigTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('db_config')->delete();

        \DB::table('db_config')->insert([
            0 => [
                'id' => 1,
                'group' => 'homepage',
                'key' => 'content',
                'settings' => '{"type":"doc","content":[{"type":"masonBrick","attrs":{"identifier":"block","values":{"header_title":null,"header_align":"center","header_tagline":null,"content":"<p>Etiam accumsan urna a mauris dapibus, nec aliquet nunc convallis. Phasellus eget justo et libero ultrices posuere. Cras euismod, arcu nec congue convallis, ipsum nunc cursus nibh, vel condimentum sapien orci non libero. Integer ullamcorper felis sit amet felis placerat, eu convallis lorem iaculis.<\\/p><p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Pellentesque sodales, velit nec euismod scelerisque, lectus est interdum eros, sit amet bibendum eros sapien in magna. Quisque suscipit ligula eu turpis dignissim, a eleifend ipsum cursus.<\\/p><p>Curabitur tincidunt, felis a elementum tincidunt, ex felis fermentum dui, eget pulvinar arcu eros eu eros. Vestibulum sollicitudin pretium velit, eget volutpat justo fermentum sit amet. Pellentesque in nulla in nisi dictum interdum.<\\/p><p>Phasellus ac eros at urna condimentum lacinia. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed bibendum, sapien a venenatis fermentum, mauris augue cursus turpis, vitae elementum massa orci sit amet massa. In hac habitasse platea dictumst.<\\/p><p>Sed vehicula magna at lacus interdum, quis laoreet nulla condimentum. Aliquam erat volutpat. Cras et nulla in turpis consectetur suscipit. Vivamus lobortis, risus sit amet cursus tincidunt, erat turpis placerat ex, ut placerat justo lorem vel ligula. Fusce non diam felis.<\\/p>","dropcap":false,"buttons":[],"theme":{"background_color":"white","use_bg":false,"blockMaxWidth":"max-w-3xl","blockMaxWidthSm":null,"blockMaxWidthMd":null,"blockMaxWidthLg":null,"blockMaxWidthXl":null,"blockMaxWidth2xl":null,"blockVerticalPadding":"py-8","blockVerticalPaddingSm":null,"blockVerticalPaddingMd":null,"blockVerticalPaddingLg":"lg:py-24","blockVerticalPaddingXl":null,"blockVerticalPadding2xl":null,"blockVerticalMargin":null,"blockVerticalMarginSm":null,"blockVerticalMarginMd":null,"blockVerticalMarginLg":null,"blockVerticalMarginXl":null,"blockVerticalMargin2xl":null}},"path":"mason.block"}}]}',
                'created_at' => null,
                'updated_at' => null,
            ],
            1 => [
                'id' => 2,
                'group' => 'homepage',
                'key' => 'slider_settings',
                'settings' => '{"height":"medium"}',
                'created_at' => null,
                'updated_at' => null,
            ],
            2 => [
                'id' => 3,
                'group' => 'homepage',
                'key' => 'slides',
                'settings' => '[{"title":null,"subtitle":null,"content":null,"button_text":null,"button_link":null,"width":"medium","is_video":false,"duration":5,"video_mp4":[],"video_webm":[],"image":null}]',
                'created_at' => null,
                'updated_at' => null,
            ],
            3 => [
                'id' => 4,
                'group' => 'homepage',
                'key' => 'seo',
                'settings' => '{"seo":{"tag_title":"HOME","meta_description":"Description home"}}',
                'created_at' => null,
                'updated_at' => null,
            ],
            4 => [
                'id' => 5,
                'group' => 'homepage',
                'key' => 'social',
                'settings' => '{"og":{"title":null,"description":null}}',
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);

    }
}
