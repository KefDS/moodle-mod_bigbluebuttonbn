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
defined('MOODLE_INTERNAL') || die();

//----OpenStack integration---

//Plugin admin settings
$string['config_cloud'] = 'Configuración General para servidores BBB en demanda';
$string['config_cloud_description']='Esta configuración <b>se usa siempre</b> que se crean servidores de BBB en demanda.';
$string['config_heat_region']='Región de Heat';
$string['config_heat_region_description']='Región donde opera el servicio Heat.';
$string['config_heat_url']='URL del servidor de OpenStack';
$string['config_heat_url_description']='El URL del servidor de OpenStack (con Heat) para la creación de los servidores de BBB.';
$string['config_yaml_stack_template_url']='Plantilla de Heat';
$string['config_yaml_stack_template_url_description']='URL del archivo con la plantilla de Heat en formato YAML.';
$string['config_json_stack_parameters_url']='Parámetros del Stack';
$string['config_json_stack_parameters_url_description']='URL del archivo, en formato JSON, con los parámetros para la plantilla de Heat, usada en la creación del stack.';
$string['config_min_openingtime']='Tiempo mínimo para reservar';
$string['config_min_openingtime_description']='Límite mínimo de tiempo para programar una videoconferencia. Debe estar en un formato días-horas-minutos. Por ejemplo: "0d-4h-30m" indica cero días, cuatro horsa y treinta minutos.';
$string['config_max_openingtime']='Tiempo máximo para reservar';
$string['config_max_openingtime_description']='Límite máximo de tiempo para programar una videoconferencia. Debe estar en un formato días-horas-minutos. Por ejemplo: "60d-10h-0m" indica sesenta días, diez horas y cero minutos.';
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
$string['config_meeting_durations']='Duraciones de las conferencias';
$string['config_meeting_durations_description']='Arreglo con las duraciones de conferencia en minutos. Deben estar en el siguiente formato: [30,60,90].';
$string['config_openstack_integration']='Servidores BBB en demanda.';
$string['config_openstack_integration_description']='Habilita la integración con OpenStack para manejar los servidores en demanda.';


//Meeting form
$string['mod_form_field_meeting_duration']='Duración';
$string['mod_form_field_meeting_duration_help']='Duración de la conferencia (en minutos).';
$string['mod_form_field_openingtime'] = 'Apertura de ingreso';
$string['mod_form_field_closingtime'] = 'Cierre de ingreso';
$string['mod_form_field_openingtime_help'] = 'Hora de inicio para que los participantes entren a la conferencia.';
$string['mod_form_field_closingtime_help'] = 'Hora de cierre para que los participantes entren a la conferencia.';
$string['bbbconferencetoosoon']='Esta conferencia no puede iniciar tan pronto. Para más información comuníquese con el Administrador.';
$string['bbbconferencetoolate']='No se puede reservar una conferencia con tanto tiempo de anticipación. Para más información comuníquese con el Administrador.';

//Tasks for OpenStack communication
$string['task_openstack_async_communication']= 'Creación de servidores de conferencias BBB con OpenStack';

//----end of OpenStack integration----