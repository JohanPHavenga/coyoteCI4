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
    protected $helpers = ['session', 'form', 'formulate', 'filesystem', 'auth', 'url', 'text_format'];
    protected $edition_model;
    protected $status_list;

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
        $this->data_to_views['menus'] = $this->get_menus();;
        // dd($this->data_to_views['menus']);
        // create session
        $this->session = \Config\Services::session();
        // check for flash data alert
        if (isset($_SESSION['alert_msg'])) {
            $this->data_to_views['flash_data'] = $this->session->getFlashdata();
        }

        // get status array
        $this->status_list = $this->edition_model->get_status_array();

        // set province dropdown        
        $province_model = model(ProvinceModel::class);
        // $this->data_to_views['province_list']=$province_model->list();

        $this->data_to_views['province_options'][0] = "National";
        foreach ($province_model->list() as $province) {
            $this->data_to_views['province_options'][$province['province_id']] = $province['province_name'];
        }
        if (!isset($_SESSION['site_province'])) {
            $_SESSION['site_province'] = 0;
        }

        // set user avatar
        $this->data_to_views['user_avatar'] = base_url('assets/images/user-avatar-placeholder.png');
        $this->data_to_views['user_status'] = 'offline';
        if (logged_in()) {
            if (user()->picture) {
                $this->data_to_views['user_avatar'] = user()->picture;
            }
            $this->data_to_views['user_status'] = 'online';
            $this->data_to_views['user_roles'] = user()->getRoles();
        }

        // set default breadcrumbs
        $this->data_to_views['bc_arr'] = $this->get_crumbs();
        // set default title
        end($this->data_to_views['bc_arr']);
        $this->data_to_views['title'] = key($this->data_to_views['bc_arr']);


        // validation on search - remove funny characters
        if ($this->request->getGet('s')) {
            $rules = ['s' => ['label'  => 'Search', 'rules'  => 'alpha_numeric_punct',],];
            if (!$this->validate($rules)) {
                $this->data_to_views['search_string'] = preg_replace("/[^A-Za-z0-9 ]/", '', $this->request->getGet('s'));
            } else {
                $this->data_to_views['search_string'] = $this->request->getGet('s');
            }
        } else {
            $this->data_to_views['search_string'] = '';
        }
    }

    public function send_email($att)
    {
        // need to always send NAME, EMAIL and MESSAGE
        $email = \Config\Services::email();
        if (isset($att['to'])) {
            $to = $att['to'];
        } else {
            $to = 'info@roadrunning.co.za';
        }
        if (isset($att['subject'])) {
            $subject = $att['subject'];
        } else {
            $subject = 'Website Contact: ' . $att['name'];
        }

        $email->setTo($to);
        $email->setCC("info@roadrunning.co.za");
        $email->setFrom($att['email'], $att['name']);
        $email->setSubject($subject);
        $email->setMessage($att['message']);

        if ($email->send()) {
            return true;
        } else {
            $data = $email->printDebugger(['headers']);
            // dd($data);
            return false;
        }
    }

    public function get_crumbs()
    {
        // setup auto crumbs from URI
        $uri = new \CodeIgniter\HTTP\URI(current_url());

        $segs = $uri->getSegments();
        // dd($segs);
        $crumb_uri = base_url();
        $total_segments = $uri->getTotalSegments();
        $crumbs['Home'] = base_url();
        for ($x = 0; $x < $total_segments; $x++) {

            if (($x == $total_segments) || ($x == 3)) {
                $crumb_uri = "";
            } else {
                $crumb_uri .= "/" . $segs[$x];
            }

            // make controller prural for event and overwrite URI
            if (($x == 1) && ($segs[$x] == "event")) {
                $segs[$x] = "race";
            }
            // make controller prural for display purposes
            if (in_array($segs[$x], ["race"])) {
                $segs[$x] = $segs[$x] . "s";
            }

            $segs[$x] = str_replace("_", " ", $segs[$x]);
            $segs[$x] = str_replace("-", " ", $segs[$x]);
            $crumbs[ucwords($segs[$x])] = $crumb_uri;

            if ($x == 3) {
                break;
            }
        }

        return $crumbs;
    }

    public function get_menus()
    {
        $menus['static_pages'] = $this->get_static_pages();
        $remove = ['switch-region', 'featured-regions', 'login', 'add-listing', 'search', 'sitemap', 'terms', 'disclaimer', 'home', 'privacy'];
        $menus['main_menu'] = array_diff_key($menus['static_pages'], array_flip($remove));
        if (logged_in()) {
            $menus['user_menu'] = $this->get_user_menu();
        }
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
                        "display" => "Bookmarked",
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
                    "province" => [
                        "display" => "Per Province",
                        "loc" => base_url("province"), //calendar/past
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
                    "about" => [
                        "display" => "About",
                        "loc" => base_url("about"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
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

    public function get_user_menu()
    {
        $menu_arr = [
            "dashboard" => [
                "display" => "Dashboard",
                "loc" => base_url("user/dashboard"),
                "icon" => "icon-material-outline-dashboard",
            ],
            "profile" => [
                "display" => "Profile",
                "loc" => base_url("user/profile"),
                "icon" => "icon-material-outline-face",
            ],
            "favourite" => [
                "display" => "Favourites",
                "loc" => base_url("race/favourite"),
                "icon" => "icon-material-outline-favorite",
            ],
            "results" => [
                "display" => "Results",
                "loc" => base_url("my-results"),
                "icon" => "icon-material-outline-assignment",
            ],
            "subscriptions" => [
                "display" => "Subscriptions",
                "loc" => base_url("user/my-subscriptions"),
                "icon" => "icon-material-baseline-mail-outline",
            ],
            "donate" => [
                "display" => "Donate",
                "loc" => base_url("user/donate"),
                "icon" => "icon-material-outline-local-atm",
            ],
            "logout" => [
                "display" => "Logout",
                "loc" => base_url("logout"),
                "icon" => "icon-material-outline-power-settings-new",
            ],
        ];
        return $menu_arr;
    }

    public function subscribe_user($user_data, $linked_to, $linked_id)
    {
        // this function will add a user to a subscription        
        $user_model = model(UserModelExtended::class);
        $usersubscription_model = model(UserSubscriptionModel::class);

        // get user id
        $user_id = $user_model->get_user_id($user_data['email']);
        // new user
        if (!$user_id) {
            $user_id = $user_model->set_user($user_data, $user_id, [2]);
        } else {
            // check if role 2 (user) exist
            // if not, add
        }

        // check if subscription exists
        $sub_exists = $usersubscription_model->exists($user_id, $linked_to, $linked_id);
        if ($sub_exists) {
            $alert = "There is already a subsciption linked to the email address for this event";
            $status = "warning";
            $icon = "info-circle";
        } else {
            $usersub_data = array(
                'user_id' => $user_id,
                'linked_to' => $linked_to,
                'linked_id' => $linked_id,
            );

            $add = $usersubscription_model->set_usersub($usersub_data);
            if ($add) {
                switch ($linked_to) {
                    case "edition":
                    case "event":
                        $alert = "Thank you. You have been added to the mailing list for this race";
                        break;
                    case "newsletter":
                        $alert = "Thank you. You have successfully been subscribed to the newsletter";
                        break;
                    default:
                        $alert = "Thank you. You have successfully been subscribed";
                        break;
                }
                $status = "success";
                $icon = "check-circle";
            } else {
                $alert = "Failed to add subsciprtion. Please contact the site administrator";
                $status = "danger";
                $icon = "minus-circle";
            }
        }
        if (!$this->request->isAJAX()) {
            // set session flash data if not posted via AJAX
            $this->session->setFlashdata([
                'alert_msg' => $alert,
                'alert_status' => $status,
                'alert_icon' => $icon,
            ]);
        }
    }

    public function get_img_url($slug, $file_list)
    {
        if (isset($file_list[1])) {
            return base_url("file/edition/" . $slug . "/logo/" . $file_list[1][0]['file_name']);
        } else {
            return base_url("assets/images/company-logo-05.png");
        }
    }

    public function formulate_status_notice($status, $info_status)
    {
        $return = [];

        switch ($status) {
            case 2:
                $msg = "<b>This event is set to DRAFT mode.</b> All detail has not yet been confirmed";
                $short_msg = "DRAFT";
                $state = "cancelled";
                break;
            case 3:
                $msg = "<strong>This event has been CANCELLED.</strong> Please use the contact form below to contact the event organisers for more detail.</a>";
                $short_msg = "Cancelled";
                $state = "cancelled";
                $icon = "times-circle";
                break;
            case 9:
                $msg = "<strong>This event has been POSTPONED until further notice.</strong> Please contact the event organisers for more detail<br>"
                    . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once a new date is set";
                $short_msg = "Postposed";
                $state = "unverified";
                break;
            default:
                switch ($info_status) {
                    case 13:
                        $msg = "<strong>PLEASE NOTE</strong> - Dates and race times has <u>not yet been confirmed</u> by the race organisers";
                        $short_msg = "Unconfimred";
                        $state = "unverified";
                        break;
                    case 14:
                        $msg = "<b>DATE CONFIRMED</b> - Waiting for race information from the organisers";
                        $short_msg = "Dates Confirmed";
                        $state = "pending";
                        break;
                    case 15:
                        $msg = "<b>EVENT CONFIRMED</b> - Information loaded has been confirmed as correct. Awaiting for complete information set from the organisers";
                        $short_msg = "Awaiting Info";
                        $state = "confirmed";
                        break;
                    case 16:
                        $msg = "<b>LISTING VERIFIED</b> - All information below has been confirmed";
                        $short_msg = "Verified";
                        $state = "verified";
                        break;
                    case 10:
                        $msg = "<b>RESULTS PENDING</b> - Waiting for results to be released.<br>Note this can take up to a week";
                        $short_msg = "Results Pending";
                        $state = "confirmed";
                        break;
                    case 11:
                        $msg = "<b>RESULTS LOADED</b> - Click on link below to view";
                        $short_msg = "Results Loaded";
                        $state = "verified";
                        break;
                    case 12:
                        $msg = "<b>NO RESULTS EXPECTED</b> - No official results will be released for this event";
                        $short_msg = "No Results Expexted";
                        $state = "unverified";
                        break;
                }
                break;
        }

        $return['msg'] = $msg;
        $return['short_msg'] = $short_msg;
        $return['state'] = $state;
        return $return;
    }

    public function get_timeago($date = false)
    {
        if (isset($date)) {
            $timeAgo = strtotime($date);
            $curTime = time();
            $timeElapsed = $curTime - $timeAgo;
            $seconds = $timeElapsed;
            $minutes = round($timeElapsed / 60);
            $hours = round($timeElapsed / 3600);
            $days = round($timeElapsed / 86400);
            $weeks = round($timeElapsed / 604800);
            $months = round($timeElapsed / 2600640);
            $years = round($timeElapsed / 31207680);
            /* Seconds  Calculation*/
            if ($seconds <= 60) {
                return 'Just Now';
            } /* Minutes */ elseif ($minutes <= 60) {
                if ($minutes == 1) {
                    return "One Minute ago";
                } else {
                    return $minutes . " time ago minutes";
                }
            } /* Hours */ elseif ($hours <= 24) {
                if ($hours == 1) {
                    return "One hour ago";
                } else {
                    return $hours . " Hours ago";
                }
            } /* Days */ elseif ($days <= 7) {
                if ($days == 1) {
                    return "One day ago";
                } else {
                    return $days . " days ago";
                }
            } /* Weeks */ elseif ($weeks <= 4.3) {
                if ($weeks == 1) {
                    return "One week ago";
                } else {
                    return $weeks . " weeks ago";
                }
            } /* Months */ elseif ($months <= 12) {
                if ($months == 1) {
                    return "One month ago";
                } else {
                    return $months . " months ago";
                }
            } /* Years */ else {
                if ($years == 1) {
                    return "One year ago";
                } else {
                    return $years . " years ago";
                }
            }
        } else {
            return "Not yet";
        }
    }
}
