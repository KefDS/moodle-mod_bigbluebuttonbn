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

/*----OpenStack integration---*/

//OpenStack integration settings
$string['openstack_integration']="Integración con OpenStack";
$string['openstack_integration_settings']="Configuración de la Integración con OpenStack";
$string['os_logs_explanation']='Seleccione qué hacer con las bitácoras de integración de OpenStack. Puede filtralas por fecha o seleccionar «Seleccionar todo» para seleccionar toda la información. ';
$string['select_all']='Seleccionar todo';
$string['openstack_servers_on_demand']= 'Usar servidores BBB en demanda. Para configurar la integración con OpenStack Settings diríjase a';
$string['openstack_integration_help'] = 'Al usar la opcion de servidores BBB en demanda, se crea un único servidor de BBB para cada actividad de conferencia creada. El ciclo de vida de la confrencia se automatiza por completo y es manejado por OpenStack.';

//OpenStack openstack_integration_forms
$string['os_logs_choose']='Escoja una';
$string['os_logs_download']='Descargar';
$string['os_logs_delete']='Eliminar';
$string['os_logs_go']='Ir';
$string['os_logs_selected_logs']='Acción:';
$string['os_logs_management']='Manejo de bitácoras';
$string['os_logs_begin_date']='Fecha de inicio';
$string['os_logs_end_date']='Fecha final';


//OpenStack openstack_logs_download.php
$string['os_logs_download_logs']='Descargar bitácoras';

//OpenStack openstack_logs_delete.php
$string['os_logs_delete_logs']='Eliminar bitácoras';
$string['os_logs_delete_confirmation_message']='La acción que desea realizar es irreversible y eliminará por completo {$a} registros de la tabla mdl_bigbluebuttonbn_os_logs correspondientes a las bitácoras de Integración con OpenStack.';
$string['os_logs_delete_success'] = 'Se eliminaron correctamente {$a} registros';


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
$string['bbbconferenceopeningsoon']='Esta conferencia ya comenzó o lo hará pronto, por lo que no es posible cambiar esta configuración.';

//Tasks for OpenStack communication
$string['task_openstack_async_communication']= 'Creación de servidores de conferencias BBB con OpenStack';

/*---- end of OpenStack integration ----*/

$string['view_message_room_closed'] = 'This room is closed.';
$string['view_message_room_ready'] = 'This room is ready.';
$string['view_message_room_open'] = 'This room is open.';
$string['view_message_conference_room_ready'] = 'This conference room is ready. You can join the session now.';
$string['view_message_conference_not_started'] = 'La confrencia aún no ha comenzado.';
$string['view_message_conference_wait_for_moderator'] = 'Waiting for a moderator to join.';
$string['view_message_conference_in_progress'] = 'This conference is in progress.';
$string['view_message_conference_has_ended'] = 'This conference has ended.';
$string['view_message_tab_close'] = 'This tab/window must be closed manually';
