<?php

namespace App\Controllers;

class Sitemap extends BaseController
{

    protected $region_model, $province_model;

    public function __construct()
    {
        $this->province_model = model(ProvinceModel::class);
        $this->region_model = model(RegionModel::class);
    }

    public function index()
    {
        $this->data_to_views['edition_list'] = $this->edition_model->list();
        $this->data_to_views['province_list'] = $this->province_model->list();
        $this->data_to_views['region_list'] = $this->region_model->list();

        return view('templates/header', $this->data_to_views)
            . view('sitemap/view')
            . view('templates/footer');
    }

    public function xml()
    {

        $data = $this->generate_sitemap_xml();

        if (!write_file('./sitemap.xml', $data)) {
            echo 'Unable to write the file';
        } else {
            echo 'File written!';
        }
    }

    private function generate_sitemap_xml()
    {
        $edition_list = $this->edition_model->list();
        $province_list = $this->province_model->list();
        $region_list = $this->region_model->list();

        // start XML
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
";

        // STATUC PAGES
        foreach ($this->data_to_views['menus']['static_pages'] as $menu_item) {
            $xml .= "<url>
    <loc>" . $menu_item['loc'] . "</loc>
    <lastmod>" . $menu_item['lastmod'] . "</lastmod>
    <changefreq>" . $menu_item['changefreq'] . "</changefreq>
    <priority>" . $menu_item['priority'] . "</priority>
</url>
";
            // sub-menu items
            if (isset($menu_item['sub-menu'])) {
                foreach ($menu_item['sub-menu'] as $sub_menu_item) {
                    // remove duplicate stuffs
                    if ($sub_menu_item['loc'] == $menu_item['loc']) {
                        continue;
                    }
                    $xml .= "<url>
    <loc>" . $sub_menu_item['loc'] . "</loc>
    <lastmod>" . $sub_menu_item['lastmod'] . "</lastmod>
    <changefreq>" . $sub_menu_item['changefreq'] . "</changefreq>
    <priority>" . $sub_menu_item['priority'] . "</priority>
</url>
";
                }
            }
        }

        // PROVINCES & REGIONS
        foreach ($region_list as $province_id => $region_list_proper) {
            $xml .= "<url>
    <loc>" . base_url('province/' . $province_list[$province_id]['province_slug']) . "</loc>
    <lastmod>" . date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")) . "</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
</url>
";
            foreach ($region_list_proper as $region_id => $region) {
                $xml .= "<url>
    <loc>" . base_url("region/" . $region['region_slug']) . "</loc>
    <lastmod>" . date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")) . "</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.7</priority>
</url>
";
            }
        }

        // CALENDAR PAGES
        foreach ($edition_list as $year => $month_list) {
            if (date('Y') - $year > 1) {
                $last_mod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year"));
                $change_freq = "yearly";
                $priority = "0.2";
            } else {
                $last_mod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week"));
                $change_freq = "weekly";
                $priority = "0.6";
            }
            $xml .= "<url>
    <loc>" . base_url('calendar/' . $year) . "</loc>
    <lastmod>$last_mod</lastmod>
    <changefreq>$change_freq</changefreq>
    <priority>$priority</priority>
</url>
";
            foreach ($month_list as $month_num => $race_list) {
                $xml .= "<url>
    <loc>" . base_url('calendar/' . $year . '/' . $month_num) . "</loc>
    <lastmod>$last_mod</lastmod>
    <changefreq>$change_freq</changefreq>
    <priority>$priority</priority>
</url>
";
            }
        }

        // EVENT PAGES
        foreach ($edition_list as $year => $month_list) {
            // don't list anything older than 3 years
            if (date('Y') - $year > 3) {
                break;
            }


            if (date('Y') - $year > 1) {
                $last_mod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year"));
                $change_freq = "yearly";
                $priority = "0.2";
            } else {
                $last_mod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 weekly"));
                $change_freq = "weekly";
                $priority = "0.6";
            }

            foreach ($month_list as $month_num => $race_list) {

                foreach ($race_list as $edition_id => $edition) {
                    // if older than 2 years
                    if (strtotime($edition['edition_date']) < strtotime('-2 year')) {
                        continue;
                    }                     
                    if (strtotime($edition['edition_date']) < strtotime('-1 year')) {
                        $last_mod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year"));
                        $change_freq = "yearly";
                        $priority = "0.2";
                    }                    
                    if (strtotime($edition['edition_date']) >= strtotime('-1 year')) {
                        $last_mod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week"));
                        $change_freq = "weekly";
                        $priority = "0.6";
                    } 
                    
                    if (strtotime($edition['edition_date']) >= strtotime('-1 month')) {
                        $last_mod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 day"));
                        $change_freq = "daily";
                        $priority = "0.8";
                    }
                    $xml .= "<url>
    <loc>" . base_url('event/' . $edition['edition_slug']) . "</loc>
    <lastmod>$last_mod</lastmod>
    <changefreq>$change_freq</changefreq>
    <priority>$priority</priority>
</url>
";
                }
            }
        }

        $xml .= "</urlset>";

        return $xml;
    }
}
