<?php

if (!defined('_PS_VERSION_'))
    exit;

class Angular extends Module
{
    public function __construct()
    {
        
        $this->name = 'angular';
        $this->version = '0.1.3';
        $this->author = 'Biomassives Group Ltd';
        $this->tab = 'administration';
        $this->displayName = $this->l('Sweetwave Manufacturers Based Pricing Options');
        $this->description = $this->l('Choose which manufacturers will display pricing and checkout');
        
        parent::__construct();
    }

    public function install()
    {
        $tables =
            Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '` (
                    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                    name VARCHAR(32) NOT NULL,
                    value LONGTEXT NOT NULL
                )') &&
            Db::getInstance()->execute('
                INSERT INTO `' . _DB_PREFIX_ . $this->name . '` VALUES(
                    NULL,
                    "appearance",
                    "{}"
                )');
        return $tables && $this->installTab($this->l('Angular Module'), "AdminParentStats") && parent::install();
    }

    public function uninstall()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . $this->name);
        return $this->uninstallTab() && parent::uninstall();
    }

    private function installTab($name, $parent)
    {
        $nameLang = array();
        foreach (Language::getLanguages() as $language)
            $nameLang[$language['id_lang']] = $name;
        $tab = new Tab();
        $tab->name = $nameLang;
        $tab->class_name = "Admin" . get_class();
        $tab->module = $this->name;
        $tab->id_parent = Tab::getIdFromClassName($parent);
        return $tab->save();
    }

    private function uninstallTab()
    {
        $idTab = Tab::getIdFromClassName("Admin" . get_class());
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            return $tab->delete();
        }
        return true;
    }
}
