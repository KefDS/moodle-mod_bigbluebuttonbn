<?php

/**
 * The openstack intregation.
 * archivo openstack/openstack
 *
 * @package   mod_bigbluebuttonbn
 * @author    Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
require_once('../../php-opencloud/autoload.php');
use OpenCloud\OpenStack;

class mod_bigbluebuttonbn_openstack {
    function __construct() {
        $client = new OpenStack('http://172.16.80.16:5000/v2.0', [
            'username' => 'carlos.mata',
            'password' => '14$dev',
            'tenantId' => '61d90c9e171a43dba0e197c824851be3'
        ]);
        $client->authenticate();
    }
}