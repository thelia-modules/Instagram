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

namespace Instagram\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Tools\URL;
use Thelia\Model\ConfigQuery;
use Instagram\Form\ConfigForm;
use Instagram\Instagram;
use Instagram\Exception\CredentialValidationException;


/**
 * Class ConfigController
 * @package Instagram\Controller
 * @author Emmanuel Nurit <enurit@openstudio.fr>
 */ 
 
class ConfigController extends BaseAdminController
{
    public function saveAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, ['instagram'], AccessManager::UPDATE)) {
            return $response;
        }

        $form = new ConfigForm($this->getRequest());
        $configForm = $this->validateForm($form);
		$username = $configForm->get('username')->getData();
		$access_token = $configForm->get('access_token')->getData();
		$photos_quantity = $configForm->get('photos_quantity')->getData();
		$debug_mode = $configForm->get('debug_mode')->getData();
		
        $errorMessage = null;
        $response = null;

        try {

            ConfigQuery::write('instagram_access_token', $access_token, 1, 1);
            ConfigQuery::write('instagram_username', $username, 1, 1);
            ConfigQuery::write('instagram_photos_quantity', $photos_quantity, 1, 1);
            ConfigQuery::write("instagram_debug_mode", $debug_mode, false, true);
            
            $response = RedirectResponse::create(URL::getInstance()->absoluteUrl('/admin/module/Instagram'));
            
            if($username && $access_token){
			    if(!extension_loaded('openssl')){ 
				    $credentialError = 'This class requires the php extension open_ssl to work as the instagram api works with httpS.'; 
				} else {
		        	$shots = file_get_contents("https://api.instagram.com/v1/users/search?q=".$configForm->get('username')->getData()."&access_token=".$configForm->get('access_token')->getData()); 
					$query = json_decode($shots);
		            if($query->meta->code!='200'){
		                $credentialError = "Bad Instagram access token";
		            }	
				}
				if (!empty($credentialError)) {  throw new CredentialValidationException($credentialError); }
	        } 
            

        } catch (CredentialValidationException $e) {
            $errorMessage = $e->getMessage();
        } catch (FormValidationException $e) {
            $errorMessage = $e->getMessage();
        }


        if (null !== $errorMessage) {
            $this->setupFormErrorContext(
                'Instagram config fail',
                $errorMessage,
                $form
            );
            $response = $this->render(
                "module-configure",
                [
                    'module_code' => 'Instagram',
                    'preview' => $preview
                ]
            );
        } 
        return $response;
    }
}
