<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['session', 'form', 'formulate', 'filesystem', 'auth', 'text_format'];
    protected $edition_model;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        // global edition model        
        $this->edition_model = model(EditionModel::class);
        // set data_to_views as empty array
        $this->data_to_views['menus']=$this->get_menus();;
        // create session
        $this->session = \Config\Services::session();
    }


    public function get_menus() {
        $menus['static_pages']=$this->get_static_pages();    
        $remove = ['switch-region', 'featured-regions', 'login', 'add-listing', 'search', 'sitemap', 'terms', 'disclaimer'];
        $menus['main_menu'] = array_diff_key($menus['static_pages'], array_flip($remove));
        return $menus;
    }

    public function get_static_pages()
    {
        return [
            "home" => [
                "display" => "Home",
                "loc" => base_url(),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 day")),
                "priority" => 1,
                "changefreq" => "daily",
            ],
            "races" => [
                "display" => "Races",
                "loc" => base_url("calendar"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                "priority" => 1,
                "changefreq" => "daily",
                "sub-menu" => [                    
                    "upcoming" => [
                        "display" => "Upcoming",
                        "loc" => base_url("race/upcoming"), //calendar
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                        "badge" => "POPULAR",
                    ],
                    "favourite" => [
                        "display" => "My Favourites",
                        "loc" => base_url("race/favourite"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                        "badge" => "BETA",
                    ],
                    "featured" => [
                        "display" => "Featured",
                        "loc" => base_url("race/featured"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                    ],
                    "top10" => [
                        "display" => "Top 10 most viewed",
                        "loc" => base_url("race/most-viewed"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                    ],
                    "history" => [
                        "display" => "History",
                        "loc" => base_url("race/history"), //calendar/past
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 0.8,
                        "changefreq" => "daily",
                    ],
                    "province" => [
                        "display" => "Per Province",
                        "loc" => base_url("province/list"), //calendar/past
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 0.8,
                        "changefreq" => "daily",
                    ],
                    "parkrun" => [
                        "display" => "parkrun",
                        "loc" => base_url("race/parkrun"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.3,
                        "changefreq" => "yearly",
                    ],
                ],
            ],
            "results" => [
                "display" => "Results",
                "loc" => base_url("results"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                "priority" => 0.8,
                "changefreq" => "weekly",
                "sub-menu" => [
                    "results" => [
                        "display" => "Race Results",
                        "loc" => base_url("race/results"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "my-results" => [
                        "display" => "My Results",
                        "loc" => "",
                        "loc" => base_url("my-results"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                        "badge" => "BETA",
                    ],
                ],
            ],
            "resources" => [
                "display" => "Resources",
                "loc" => base_url("faq"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                "priority" => 0.8,
                "changefreq" => "weekly",
                "sub-menu" => [
                    "faq" => [
                        "display" => "FAQ",
                        "loc" => base_url("faq"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                    "training" => [
                        "display" => "Training Programs",
                        "loc" => base_url("training-programs"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                    "friends" => [
                        "display" => "Friends",
                        "loc" => base_url("friends"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                ],
            ],
            "about" => [
                "display" => "About",
                "loc" => base_url("about"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                "priority" => 0.5,
                "changefreq" => "monthly",
            ],
            "contact" => [
                "display" => "Contact",
                "loc" => base_url("contact"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.8,
                "changefreq" => "yearly",
                "sub-menu" => [
                    "contact-us" => [
                        "display" => "Contact Us",
                        "loc" => base_url("contact"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.8,
                        "changefreq" => "yearly",
                    ],
                    "support" => [
                        "display" => "Support the site",
                        "loc" => base_url("support"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.8,
                        "changefreq" => "yearly",
                    ],
                    "newsletter" => [
                        "display" => "Newsletter",
                        "loc" => base_url("newsletter"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.6,
                        "changefreq" => "yearly",
                        "badge" => "POPULAR",
                    ],
                ],
            ],
            "switch-region" => [
                "display" => "Switch Region",
                "loc" => base_url("region/switch"), //version
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.5,
                "changefreq" => "yearly",
            ],
            "featured-regions" => [
                "display" => "Show all regions",
                "loc" => base_url("region"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                "priority" => 0.8,
                "changefreq" => "monthly",
                "sub-menu" => [
                    "cape-town" => [
                        "display" => "Cape Town",
                        "loc" => base_url("region/capetown"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 1,
                        "changefreq" => "weekly",
                        "badge" => "POPULAR",
                    ],
                    "gauteng" => [
                        "display" => "Gauteng",
                        "loc" => base_url("region/gauteng"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "kzn-coast" => [
                        "display" => "KZN Coast",
                        "loc" => base_url("region/kzn-coast"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "garden-route" => [
                        "display" => "Garden Route",
                        "loc" => base_url("region/garden-route"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                ],
            ],
            "login" => [
                "display" => "Login",
                "loc" => base_url("login"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 1,
                "changefreq" => "yearly",
            ],
            "add-listing" => [
                "display" => "Add Race Listing",
                "loc" => base_url("event/add"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.6,
                "changefreq" => "yearly",
            ],
            "search" => [
                "display" => "Search",
                "loc" => base_url("search"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")),
                "priority" => 0.8,
                "changefreq" => "weekly",
            ],
            "sitemap" => [
                "display" => "Sitemap",
                "loc" => base_url("sitemap"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")),
                "priority" => 0.5,
                "changefreq" => "daily",
            ],
            "terms" => [
                "display" => "Terms & Conditions",
                "loc" => base_url("terms-conditions"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
            "disclaimer" => [
                "display" => "Disclaimer",
                "loc" => base_url("disclaimer"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
            "privacy" => [
                "display" => "Privacy Policy",
                "loc" => base_url("privacy-policy"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
        ];
    }
    
}
