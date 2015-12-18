<?php
/*****************************************************************************************
 * X2Engine Open Source Edition is a customer relationship management program developed by
 * X2Engine, Inc. Copyright (C) 2011-2015 X2Engine Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY X2ENGINE, X2ENGINE DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact X2Engine, Inc. P.O. Box 66752, Scotts Valley,
 * California 95067, USA. or at email address contact@x2engine.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * X2Engine" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by X2Engine".
 *****************************************************************************************/

class MobileViewLayoutRenderer extends MobileLayoutRenderer {

    public $JSClass = 'MobileViewLayoutRenderer';

    private $_packages;
    public function getPackages () {
        return array_merge (parent::getPackages (), array (
            'MobileViewLayoutRenderer' => array(
                'baseUrl' => Yii::app()->controller->assetsUrl,
                'js' => array(
                    'js/MobileViewLayoutRenderer.js',
                ),
                'depends' => array ('MobileLayoutRenderer')
            ),
        ));
    }

    public function renderLabel ($text) {
        $html = '';
        $html .= CHtml::openTag ('div', array ('class' => 'field-label'));
        $html .= $text;
        $html .= CHtml::closeTag ('div');
        return $html;
    }

    /**
     * Prepend name field to generated mobile layout, if it's not there already
     */
    public function getLayoutData () {
        $layoutData = parent::getLayoutData ();
        if (!$this->mobileLayout) {
            $nameField = 'name';
            if ($this->module instanceof Profile) {
                $nameField = 'fullName';
            }
            if (array_search ($nameField, $layoutData, true) === false) {
                array_unshift ($layoutData, $nameField);
            }
        }
        return $layoutData;
    }

    public function renderLayout () {
        $html = '';

        //$html .= $this->renderName ();
        foreach ($this->layoutData as $fieldName) {
            $field = $this->model->getField ($fieldName);
            if (!$this->canView ($field)) continue;
            if (!$field) {
                continue;
            }
            $html .= $this->renderField (
                $field,
                $this->renderLabel (
                    $field->attributeLabel
                ).
                $this->renderValue ($fieldName));
        }
        return $html;
    }

    public function getLayout () {
        return FormLayout::model()->findByAttributes(
            array(
                'model' => ucfirst($this->modelName),
                'defaultView' => 1,
                'scenario' => 'Default'
            ));
    }

}

?>