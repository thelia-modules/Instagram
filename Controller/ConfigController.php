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
        $error_message = null;
        $response = null;

        try {
            $configForm = $this->validateForm($form);

            ConfigQuery::write('instagram_access_token', $configForm->get('access_token')->getData(), 1, 1);
            ConfigQuery::write('instagram_username', $configForm->get('username')->getData(), 1, 1);
            ConfigQuery::write('instagram_photos_quantity', $configForm->get('photos_quantity')->getData(), 1, 1);
            $response = RedirectResponse::create(URL::getInstance()->absoluteUrl('/admin/module/Instagram'));

        } catch (FormValidationException $e) {
            $errorMsg = $e->getMessage();
        }

        if (null !== $error_message) {
            $this->setupFormErrorContext(
                'Instagram config fail',
                $error_message,
                $form
            );
            $response = $this->render(
                "module-configure",
                [
                    'module_code' => 'Instagram'
                ]
            );
        }

        return $response;
    }
}
