<?php

set_include_path(realpath(dirname(__FILE__) . '/../'));

require_once 'Tweetgater/Twitter.php';

/**
 * Does all interaction with the twitter API for the display layer
 * 
 * @author jfaustin
 *
 */
class Tweetgater_Display
{
    /**
     * Displayes the timeline for the registered user
     * 
     * @param int $page Page of tweets to display
     * @return string HTML of the tweets
     */
    public static function timeline($page = 1)
    {
        $tweetgater = new Tweetgater_Twitter();
        
        $error = '';
        
        try {
            $timeline = $tweetgater->getTimeline($page);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        
        $ret = '';
        
        if ($error == '') {
			$ret .= '<div class="tweetHeader">'
				 . '<h2>Tweets from UNL Community</h2>'
				 . '<a href="#orgs">&rarr; <span>See all accounts</span></a>' 
				 . '</div>'
                 .  '<div style="clear:both;"></div>'
			;
            foreach ($timeline as $t) {
                $ret .= '<div class="tweet">'
                     . '    <div class="avatar">'
					 . '<a class="username" href="http://twitter.com/' . $t['user-screen_name'] . '">'
                     . '        <img src="' . $t['user-profile_image_url'] . '" alt="' . $t['user-name'] . '" width="48" height="48" /></a>'
                     . '    </div>'
                     . '    <div class="text">'
                     . '        <a class="username" href="http://twitter.com/' . $t['user-screen_name'] . '">' . $t['user-screen_name'] . '</a> <br />' . $t['text']
                     . '    </div>'
                     . '    <div class="origination"> ' . $t['elapsed_time'] . ' from ' . $t['source']
                     . (($t['in_reply_to_screen_name'] != '') ? ' <a class="user" href="http://www.twitter.com/' . $t['in_reply_to_screen_name'] . '/status/' . $t['in_reply_to_status_id'] . '">in reply to ' . $t['in_reply_to_screen_name'] . '</a>' : '')
                     . '    </div>'
                     . '    <div style="clear:both;"></div>'
                     . '</div>'
					 . '<div class="wrap">'
					 . '	<div class="shadow"></div>'
					 . '</div>'
                     ;
            }
            
            $ret .= '<br />'
                 . '<a class="moreButton" href="index.php?page=' . ($page + 1) . '">More...</a>'
                 ;
        } else {
            $ret = $error;
        }
        
        return $ret;
    }	
    
    /**
     * Displays list of accounts for the registered user
     * 
     * @return $ret HTML of the accounts
     */
    public static function accounts()
    {
        $tweetgater = new Tweetgater_Twitter();
        
        $error = '';
        try {
            $friends = $tweetgater->getFriends();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        
        $ret = '';
        
        if ($error == '') {
            $ret .= '<h2 class="tweetHeader">' . count($friends) . ' Organizations are Tweeting</h2>'
				 .  '<div style="clear:both;"></div>';
            
            $i = 0; 
            foreach ($friends as $f) {
                $ret .= '<div class="tweet friend ' . (($i % 2 == 0) ? 'row1' : 'row2') . '">'
                     . '    <div class="avatar">'
					 . '<img src="' . $f['profile_image_url'] . '" alt="' . $f['name'] . '" width="48" height="48" /></div>'
                     . '    <div class="text">'
                     . '        <span class="name"><strong>' . $f['name'] . '</strong></span><br />'
                     . (($f['description'] != '') ? $f['description'] . '<br />' : '')
                     . '        <a class="follow" href="http://twitter.com/' . $f['screen_name'] . '">Follow @' . $f['screen_name'] . '</a>'
                     . '    </div>'
                     . '    <div style="clear:both;"></div>'
                     . '</div>'
                     ;
                     
                $i++;
            }         
        } else {
            $ret = $error;
        }
        
        return $ret;
    }
    
    /**
     * Displays valid RSS feed of the tweets for the registered user
     * 
     * @return nothing, prints feed to stdout
     */
    public static function feed()
    {
        $tweetgater = new Tweetgater_Twitter();
        
        $down = false;
        try {
            $timeline = $tweetgater->getTimeline();
        } catch (Exception $e) {
            $down = true;
        }
        
        $config = $tweetgater->getConfigFile();
        
        $fa = array();
        
        $fa['title'] = $config->feed->title;
        $fa['link']  = $config->site->url. '/feed.php';
        $fa['charset'] = 'UTF-8';
        $fa['author']  = $config->feed->title;
        
        $entries = array();
        
        if (!$down) {
            foreach ($timeline as $t) {
            
                $temp = array();
            
                $temp['title'] = 'Tweet by @' . $t['user-screen_name'];
                $temp['link'] = 'http://www.twitter.com/' . $t['user-screen_name'] . '/status/' . $t['id'];
                $temp['description'] = $t['text'] . '<br /><br />Follow @<a href="http://www.twitter.com/' . $t['user-screen_name'] . '">' . $t['user-screen_name'] . '</a>';
                $temp['lastUpdate'] = strtotime($t['created_at']);
        
                $entries[] = $temp;
            }
        }
        
        $fa['entries'] = $entries;
        
        // importing a rss feed from an array
        $rssFeed = Zend_Feed::importArray($fa, 'rss');
        
        
        // send http headers and dump the feed
        $rssFeed->send();        
    }
    
    /**
     * Returns the twitpics of size and quantity for registered user
     * 
     * @param string $quantity Number of pics to show
     * @param string(mini|thumb|large) $style Style of pics to show
     */
    public static function twitpic($quantity = 5, $style = 'mini')
    {
        $style = (!in_array($style, array('mini', 'thumb', 'large'))) ? 'mini' : $style;
        
        $styleOptions = array(
            'mini'  => 'http://twitpic.com/show/mini/%1$s',
            'thumb' => 'http://twitpic.com/show/thumb/%1$s',
            'large' => 'http://twitpic.com/show/large/%1$s',
        );
       
        $tweetgater = new Tweetgater_Twitter();
        
        $error = '';
        try {
            $twitpic = $tweetgater->getTwitPicImages($quantity);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        
        $ret = '';
        
        if ($error == '') {
            $ret = '<ul>';
            foreach ($twitpic as $t) {
                $ret .= '<li>'
                     . '<a href="' . sprintf('http://twitpic.com/%1$s', $t) . '" title="Twitpic">'
                     . '<img src="' . sprintf($styleOptions[$style], $t) . '" alt="twitpic" class="thumb" />'
                     . '</a>'
                     . '</li>'
                     ; 
            }
            $ret .= '</ul>';
            
            return $ret;
        }

        return $error;
    }  

    /**
     * Display search results
     *
     * @param string $terms Search terms
     * @param int $page integer of the page of the timeline to request
     * @return $ret HTML of search results
     */
    public static function search($terms, $page = 1) 
    {
        $badWords = array('fuck', 'bitch', 'shit');
		$tweetgater = new Tweetgater_Twitter();
        $error = '';
        
        try {    
            $searchResults = $tweetgater->search($terms, $page);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        
        $ret = '';
        if ($error == '') {
			$ret .= '<div class="tweetHeader">'
				 .  '<h2>Tweets about UNL</h2>'
				 .  '</div>' 
                 .  '<div style="clear:both;"></div>'
			;
			foreach ($searchResults as $t) {
				
				/* Bad words filter */
				foreach ($badWords as $check) {
					$curse = substr_count(strtolower($t['text']), $check);
					if ($curse) {
						continue 2;
					}
				}
					
				
                $ret .= '<div class="tweet">'
                     . '    <div class="avatar">'
                     . '        <a class="username" href="http://twitter.com/' . $t['user-screen_name'] . '">' . '<img src="' . $t['user-profile_image_url'] . '" alt="' . $t['user-name'] . '" width="48" height="48" /></a>'
                     . '    </div>'
                     . '    <div class="text">'
                     . '        <a class="username" href="http://twitter.com/' . $t['user-screen_name'] . '">' . $t['user-name'] . '</a> ' . $t['text']
                     . '    </div>'
                     . '    <div class="origination"> ' . $t['elapsed_time'] . ' from ' . $t['source']
                     . (($t['in_reply_to_screen_name'] != '') ? ' <a class="user" href="http://www.twitter.com/' . $t['in_reply_to_screen_name'] . '/status/' . $t['in_reply_to_status_id'] . '">in reply to ' . $t['in_reply_to_screen_name'] . '</a>' : '')
                     . '    </div>'
                     . '    <div style="clear:both;"></div>'
                     . '</div>'
                     . '<div class="wrap">'
					 . '	<div class="shadow"></div>'
					 . '</div>'
                     ;
            }
            
            $ret .= '<br /><br />'
                 //. '<a class="moreButton" href="search.php?page=' . ($page + 1) . '">More...</a>'
                 ;    
				 
            return $ret;
        }
        
        return $error;    
    }
     
}