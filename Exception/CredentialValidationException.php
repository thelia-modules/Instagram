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


namespace Instagram\Exception;


/**
 * Class CredentialValidationException
 * @package Instagram\Exception
 * @author Emmanuel Nurit <enurit@openstudio.fr>
 */ 
class CredentialValidationException extends \RuntimeException
{

    protected $silent = true;

    public function __construct($message, $silent = true)
    {
        $this->silent = $silent;

        parent::__construct($message);
    }

    /**
     * @return boolean
     */
    public function isSilent()
    {
        return $this->silent;
    }

    /**
     * @param boolean $silent
     */
    public function setSilent($silent)
    {
        $this->silent = $silent;
    }
}
