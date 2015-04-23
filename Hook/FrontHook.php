<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Instagram\Hook;
use Instagram\Instagram;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\ConfigQuery;

 
class FrontHook extends BaseHook {

    public function onMainFooterBody(HookRenderBlockEvent $event)
    {
	    $username=ConfigQuery::read('instagram_username');
	    $access_token=ConfigQuery::read('instagram_access_token');
	    $count=ConfigQuery::read('instagram_photos_quantity');
	    
	    if(!empty($username) && !empty($access_token)){
	        $isg = new Instagram($username,$access_token); //instanciates the class with the parameters
	        $shots = $isg->getUserMedia(array('count'=>$count)); //Get the shots from instagram
	        $content = $isg->simpleDisplay($shots);
        } else {
        	$content = 'Please update your settings to provide a valid username and access token';
        }

        if ("" != $content){
            $event->add(array(
                "id" => "instagram-footer-body",
                "class" => "instagram",
                "title" => $this->trans("Instagram", array(), "instagram"),
                "content" => $content
            ));
        }
    }

} 