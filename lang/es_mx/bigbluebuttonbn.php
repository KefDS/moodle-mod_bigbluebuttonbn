<?php
/**
 * Language File
 *
 * @package   mod_bigbluebuttonbn
 * @author
 * @author
 * @copyright 2010-2015 Blindside Networks Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

//----OpenStack integration---

//Plugin admin settings
$string['config_cloud'] = 'Configuración General para servidores BBB en demanda';
$string['config_cloud_description']='Esta configuración <b>se usa siempre</b> que se crean servidores de BBB en demanda.';
$string['config_heat_region']='Región de Heat';
$string['config_heat_region_description']='Región donde opera el servicio Heat.';
$string['config_heat_url']='URL del servidor de OpenStack';
$string['config_heat_url_description']='El URL del servidor de OpenStack (con Heat) para la creación de los servidores de BBB.';
$string['config_json_stack_parameters']='Parámetros del Stack';
$string['config_json_stack_parameters_description']='Parámetros para la plantilla de Heat, usada en la creación del stack. Deben estar en formato JSON: {"param1" : "val1", "param2" : "val2"}';
$string['config_openstack_credentials']='Credenciales de OpenStack';
$string['config_openstack_credentials_description']='Credenciales necesarias para conectarse a los servicios de OpenStack.';
$string['config_openstack_username']='Nombre de usuario';
$string['config_openstack_username_description']='Nombre de usuario para conectarse a los servicios de OpenStack.';
$string['config_openstack_password']='Contraseña';
$string['config_openstack_password_description']='Contraseña para conectarse a los servicios de OpenStack.';
$string['config_openstack_tenant_id']='Tenant ID';
$string['config_openstack_tenant_id_description']='Identificador del projecto (<i>tenant</i>) para conectarse a los servicios de OpenStack. ';
$string['config_shared_secret_on_demand']='Shared Secret de los servidores de BigBlueButton';
$string['config_shared_secret_on_demand_description']='El <i>secret salt</i> de los servidores de BigBlueButton.';

//Meeting form
$string['mod_form_field_meeting_duration']='Duración';
$string['mod_form_field_meeting_duration_help']='Duración de la conferencia (en minutos).';
$string['mod_form_field_openingtime'] = 'Apertura de ingreso';
$string['mod_form_field_closingtime'] = 'Cierre de ingreso';
$string['mod_form_field_openingtime_help'] = 'Hora de inicio para que los participantes entren a la conferencia.';
$string['mod_form_field_closingtime_help'] = 'Hora de cierre para que los participantes entren a la conferencia.';

//Tasks for OpenStack communication
$string['task_openstack_async_communication']= 'Creación de servidores de conferencias BBB con OpenStack';

//----end of OpenStack integration----
