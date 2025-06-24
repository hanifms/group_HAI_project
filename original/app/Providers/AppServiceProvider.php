<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Booking;
use App\Models\TravelPackage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

/**
 * AppServiceProvider
 *
 * This service provider registers any application services, defines macros,
 * custom Blade directives, and permission gates used throughout the application.
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register singleton services if needed
        $this->app->singleton('security.helper', function ($app) {
            return new \App\Helpers\SecurityHelper();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Define permission gates
        $this->defineGates();

        // Register custom blade directives
        $this->registerBladeDirectives();

        // Define custom macros
        $this->defineCustomMacros();
    }

    /**
     * Define application permission gates
     *
     * @return void
     */
    private function defineGates(): void
    {
        // Define admin gate - checks if user is an admin
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        // Define manage-booking gate - allows admin or owner to manage booking
        Gate::define('manage-booking', function (User $user, Booking $booking) {
            return $user->isAdmin() || $booking->user_id === $user->id;
        });

        // Define manage-travel-package gate - only admins can manage travel packages
        Gate::define('manage-travel-package', function (User $user, TravelPackage $package) {
            return $user->isAdmin();
        });

        // Define manage-users gate - only admins can manage users
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });
    }

    /**
     * Register custom Blade directives
     *
     * @return void
     */
    private function registerBladeDirectives(): void
    {
        // Format currency directive
        Blade::directive('currency', function ($expression) {
            return "<?php echo '$' . number_format($expression, 2); ?>";
        });

        // Format date directive
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo ($expression)->format('M d, Y'); ?>";
        });

        // Admin role check directive
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        // User role check directive
        Blade::if('user', function () {
            return auth()->check() && !auth()->user()->isAdmin();
        });
    }

    /**
     * Define custom macros for various classes
     *
     * @return void
     */
    private function defineCustomMacros(): void
    {
        // Add a 'search' scope to the query builder
        Builder::macro('search', function ($field, $string) {
            return $string ? $this->where($field, 'like', '%'.$string.'%') : $this;
        });

        // Add a 'truncate' method to the Str facade
        Str::macro('truncateHtml', function ($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
            if ($considerHtml) {
                // If the plain text is shorter than the maximum length, return the whole text
                if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                    return $text;
                }

                // Splits all html-tags to scanable lines
                preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);

                $total_length = strlen($ending);
                $open_tags = [];
                $truncate = '';

                foreach ($lines as $line_matchings) {
                    // If there is any html-tag in this line, handle it and add it (uncounted) to the output
                    if (!empty($line_matchings[1])) {
                        // If it's an "empty element" with or without xhtml-conform closing slash
                        if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                            // Do nothing
                        } // If tag is a closing tag
                        else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                            // Delete tag from $open_tags list
                            $pos = array_search($tag_matchings[1], $open_tags);
                            if ($pos !== false) {
                                unset($open_tags[$pos]);
                            }
                        } // If tag is an opening tag
                        else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                            // Add tag to the beginning of $open_tags list
                            array_unshift($open_tags, strtolower($tag_matchings[1]));
                        }
                        // Add html-tag to $truncate'd text
                        $truncate .= $line_matchings[1];
                    }

                    // Calculate the length of the plain text part of the line; handle entities as one character
                    $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                    if ($total_length+$content_length> $length) {
                        // The number of characters which are left
                        $left = $length - $total_length;
                        $entities_length = 0;
                        // Search for html entities
                        if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                            // Calculate the real length of all entities in the legal range
                            foreach ($entities[0] as $entity) {
                                if ($entity[1]+1-$entities_length <= $left) {
                                    $left--;
                                    $entities_length += strlen($entity[0]);
                                } else {
                                    // No more characters left
                                    break;
                                }
                            }
                        }
                        $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                        // Maximum length is reached, so get off the loop
                        break;
                    } else {
                        $truncate .= $line_matchings[2];
                        $total_length += $content_length;
                    }

                    // If the maximum length is reached, get off the loop
                    if($total_length>= $length) {
                        break;
                    }
                }

                // If the words shouldn't be cut in the middle...
                if (!$exact) {
                    // ...search the last occurance of a space...
                    $spacepos = strrpos($truncate, ' ');
                    if (isset($spacepos)) {
                        // ...and cut the text in this position
                        $truncate = substr($truncate, 0, $spacepos);
                    }
                }

                // Add the defined ending to the text
                $truncate .= $ending;

                // Close all unclosed html-tags
                foreach ($open_tags as $tag) {
                    $truncate .= '</' . $tag . '>';
                }

                return $truncate;
            } else {
                // If HTML tags are not considered
                return substr($text, 0, $length) . $ending;
            }
        });
    }
}
