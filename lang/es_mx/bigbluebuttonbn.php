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
$string['config_json_stack_parameters']='Parámetros del Stack';
$string['config_json_stack_parameters_description']='Parámetros para la plantilla de Heat, usada en la creación del stack. Deben estar en formato JSON: {"param1" : "val1", "param2" : "val2"}';
$string['config_min_openingtime']='Tiempo mínimo para reservar';
$string['config_min_openingtime_description']='Límite mínimo de tiempo para programar una videoconferencia. Debe estar en un formato D:H, donde D es el número de días y H es el número de horas. Por ejemplo: "00:15" indica cero días y quince minutos.';
$string['config_max_openingtime']='Tiempo máximo para reservar';
$string['config_max_openingtime_description']='Límite máximo de tiempo para programar una videoconferencia. Debe estar en un formato D:H, donde D es el número de días y H es el número de horas. Por ejemplo: "100:00" indica cien días y cero minutos.';
$string['config_openstack_credentials']='Credenciales de OpenStack';
$string['config_openstack_credentials_description']='Credenciales necesarias para conectarse a los servicios de OpenStack.';
$string['config_openstack_username']='Nombre de usuario';
$string['config_openstack_username_description']='Nombre de usuario para conectarse a los servicios de OpenStack.';
$string['config_openstack_password']='Contraseña';
$string['config_openstack_password_description']='Contraseña para conectarse a los servicios de OpenStack.';
$string['config_openstack_tenant_id']='Tenant ID';
$string['config_openstack_tenant_id_description']='Identificador del projecto (<i>tenant</i>) para conectarse a los servicios de OpenStack. ';
$string['config_json_meeting_durations']='Duraciones de las conferencias';
$string['config_json_meeting_durations_description']='Arreglo con las duraciones de conferencia en minutos. Deben estar en formato JSON: [30,60,90].';
$string['config_openstack_integration']='Servidores BBB en demanda.';
$string['config_openstack_integration_description']='Habilita la integración con OpenStack para manejar los servidores en demanda.';

//Meeting form
$string['mod_form_field_meeting_duration']='Duración';
$string['mod_form_field_meeting_duration_help']='Duración de la conferencia (en minutos).';
$string['bbbconferencetoosoon']='Esta conferencia no puede iniciar tan pronto. Para más información comuníquese con el Administrador.';
$string['bbbconferencetoolate']='No se puede reservar una conferencia con tanto tiempo de anticipación. Para más información comuníquese con el Administrador.';

//Tasks for OpenStack communication
$string['task_openstack_async_communication']= 'Creación de servidores de conferencias BBB con OpenStack';



//----end of OpenStack integration----

$string['view_message_room_closed'] = 'La videoconferencia está cerrada.';
$string['view_message_room_ready'] = 'La videoconferencia esta lista.';
$string['view_message_room_open'] = 'La videoconferencia está abierta';
$string['view_message_conference_room_ready'] = 'La videoconferencia está lista. Puede ingresar cuando guste.';
$string['view_message_conference_not_started'] = 'Esta videoconferencia aún no ha iniciado';
$string['view_message_conference_wait_for_moderator'] = 'Esperando al moderador para entrar.';
$string['view_message_conference_in_progress'] = 'La conferencia ya ha empezado.';
$string['view_message_conference_has_ended'] = 'La conferencia ya ha finalizado.';
$string['view_message_tab_close'] = 'Esta ventana/pestaña debe cerrarse manualmente.';


$string['mod_form_field_openingtime'] = 'Apertura de ingreso';
$string['mod_form_field_closingtime'] = 'Cierre de ingreso';
$string['mod_form_field_openingtime_help'] = 'Hora de inicio para que los participantes entren a la conferencia.';
$string['mod_form_field_closingtime_help'] = 'Hora de cierre para que los participantes entren a la conferencia.';
