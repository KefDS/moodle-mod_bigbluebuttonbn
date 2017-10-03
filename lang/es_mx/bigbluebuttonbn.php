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

//----Openstack Interface
//OpenStack openstack_integration_forms.php
$string['os_records_begin_date']='Fecha de inicio';
$string['os_records_choose']='Escoja una';
$string['os_records_delete']='Eliminar';
$string['os_records_download']='Descargar';
$string['os_records_end_date']='Fecha final';
$string['os_records_go']='Ir';
$string['os_records_selected']='Acción:';
$string['os_records_select_all']='Seleccionar todo';
//----OS logs
$string['os_logs_management']='Manejo de bitácoras';
$string['os_logs_explanation']='Seleccione qué hacer con las bitácoras de integración de OpenStack. Puede filtralas por fecha o seleccionar «Seleccionar todo» para seleccionar toda la información. ';
//----Reservations
$string['reservations_records_managememod_form_field_meeting_durationnt']='Manejo de reservaciones';
$string['reservations_records_explanation']='Seleccione qué hacer con los registros de reservaciones. Puede filtralos por fecha o seleccionar «Seleccionar todo» para seleccionar toda la información. ';
//OpenStack openstack_download_records.php
$string['os_download_records']='Descargar registros';
//OpenStack openstack_records_delete.php
$string['os_delete_records']='Eliminar registros';
$string['os_delete_records_success'] = 'Se eliminaron correctamente {$a} registros';
//----OS logs
$string['os_delete_os_logs_confirmation']='La acción que desea realizar es irreversible y eliminará por completo {$a} registros de la tabla mdl_bigbluebuttonbn_os_logs correspondientes a las bitácoras de Integración con OpenStack.';
//----Reservations
$string['os_delete_reservations_records_confirmation']='La acción que desea realizar es irreversible y eliminará por completo {$a} registros de la tabla mdl_bigbluebuttonbn_reservations correspondientes a las reservaciones de Integración con OpenStack.';

//----OpenStack admin settings
//Heading
$string['openstack_integration_help'] = 'Al usar la opcion de servidores BBB en demanda, se crea un único servidor de BBB para cada actividad de conferencia creada. El ciclo de vida de la confrencia se automatiza por completo y es manejado por OpenStack.';
$string['openstack_servers_on_demand']= 'Usar servidores BBB en demanda mediante la Integración con OpenStack. Para administrar reservaciones y bitácoras diríjase a';
$string['openstack_integration_modules']= 'Modulos de Integración de OpenStack.';
$string['openstack_settings_note']= '<b>Nota:</b>Si desea administrar las opciones relacionadas con la integración de OpenStack, asegúrese de marcar la opción "Servidores BBB en demanda". Posteriormente presione el botón de guardar cambios al final de la página.';
$string['config_cloud_description']='<b>Configuración General</b> para servidores BBB en demanda. Esta configuración se usa siempre que se crean servidores de BBB en demanda.';
$string['config_heat_region']='Región de Heat';
$string['config_heat_region_description']='Región donde opera el sistema de orquestación de OpenStack.';
$string['config_heat_url']='URL del servidor de OpenStack';
$string['config_heat_url_description']='El URL del servidor de OpenStack (con Heat) para la creación de los servidores de BBB.';
$string['config_yaml_stack_template_url']='Plantilla de Heat';
$string['config_yaml_stack_template_url_description']='URL del archivo con la plantilla de Heat en formato YAML.';
$string['config_json_stack_parameters_url']='Parámetros del Stack';
$string['config_json_stack_parameters_url_description']='URL del archivo, en formato JSON, con los parámetros para la plantilla de Heat, usada en la creación del stack.';
$string['config_min_openingtime']='Tiempo mínimo para reservar';
$string['config_min_openingtime_description']='Límite mínimo de tiempo para programar una videocomunicación. Debe estar en un formato días-horas-minutos. Por ejemplo: "0d-4h-30m" indica cero días, cuatro horas y treinta minutos.';
$string['config_max_openingtime']='Tiempo máximo para reservar';
$string['config_max_openingtime_description']='Límite máximo de tiempo para programar una videocomunicación. Debe estar en un formato días-horas-minutos. Por ejemplo: "60d-10h-0m" indica sesenta días, diez horas y cero minutos.';
$string['config_max_simultaneous_instances']='Número máximo de servidores BBB';
$string['config_max_simultaneous_instances_description']='Capacidad máxima de servidores de BBB corriendo al mismo tiempo';
$string['config_openstack_credentials_description']='<b>Credenciales de OpenStack</b>. Credenciales necesarias para conectarse a los servicios de OpenStack.';
$string['config_openstack_username']='Nombre de usuario';
$string['config_openstack_username_description']='Nombre de usuario para conectarse a los servicios de OpenStack.';
$string['config_openstack_password']='Contraseña';
$string['config_openstack_password_description']='Contraseña para conectarse a los servicios de OpenStack.';
$string['config_openstack_tenant_id']='Tenant ID';
$string['config_openstack_tenant_id_description']='Identificador del projecto (<i>tenant</i>) para conectarse a los servicios de OpenStack. ';
$string['config_meeting_durations']='Duraciones de las conferencias';
$string['config_meeting_durations_description']='Arreglo con las duraciones de conferencia en minutos. Deben estar en el siguiente formato: [30,60,90].';
$string['config_conference_extra_time']='Tiempo extra para videocomunicaciones';
$string['config_conference_extra_time_description']='Tiempo extra (en minutos) agregado a videocomunicaciones antes de destruir los servidores BBB.';
$string['config_openstack_integration']='Servidores BBB en demanda.';
$string['config_openstack_integration_description']='Habilita la integración con OpenStack para manejar los servidores en demanda.';
$string['config_reservation_user_list_logic']='Lista blanca/negra de usuarios para reservaciones';
$string['config_reservation_user_list_logic_description']='Marque para usar como una lista de usuarios bloqueados(lista negra), dejela en blanco para usar como lista de usuarios autorizados(lista blanca) a reservar videocomunicaciones.';
$string['config_authorized_reservation_users_list']="Lista de usuarios autorizados/bloqueados";
$string['config_authorized_reservation_users_list_description']='Lista separada por comas de los nombres de usuario (username) autorizados o bloqueados según la configuración lista blanca/lista negra. El formato debe ser «username1,username2,username3».';
$string['config_reservation_module_enabled']='Activar modulo de reservaciones';
$string['config_reservation_module_enabled_description']='Activar o desactivar el código de reservaciones para limitar la cantidad de reservaciones de acuerdo a los recursos.';
$string['openstack_time_description']='<b>Nota:</b> el tiempo total de reservación de un recurso es igual a la suma del «Tiempo mínimo para reservar» + «Tiempo extra para videocomunicación» + {$a} minutos. Para cambiar el último parámetro debe hacerlo directamente en el código fuente del plugin.';
$string['openstack_reservation_settings']= '<b>Módulo de Reservaciones</b>. Configuraciones generales del Módulo de Reservaciones.';

//----Meeting form
$string['mod_form_field_meeting_duration']='Duración(en minutos)';
$string['mod_form_field_meeting_duration_help']='Duración de la conferencia (en minutos).';
$string['mod_form_field_custom_closingtime']='Cierre de ingreso(en minutos)';
$string['mod_form_field_custom_closingtime_help']='Tiempo máximo para que los participantes entren a la conferencia.';
$string['mod_form_field_finish_time']='<b>Hora de finalización: </b>';
$string['bbbconferencetoosoon']='Esta conferencia no puede iniciar tan pronto. Por favor escoja un tiempo de inicio posterior a {$a}.';
$string['bbbconferencetoolate']='No se puede reservar una conferencia con tanto tiempo de anticipación. Por favor intente un horario anterior.';
$string['bbbconferenceopeningsoon']='Esta conferencia ya comenzó o lo hará pronto, por lo que no es posible cambiar esta configuración.';
$string['bbb_closingtime_too_big']='El tiempo de cierre de entrada a la videocomunicación no puede ser mayor que la duración total';
$string['bbb_reservation_disable']='Es necesario tener una autorización del administrador del sitio para poder crear videocomunicaciones. Para más información contacte al administrador.';
$string['reservation_system_busy']='Sistema de reservaciones ocupado, por favor vuelva intentarlo.';
$string['unsuficient_availability']='No hay cupo suficiente para el horario solicitado. Por favor escoja otra hora de inicio.';

//Tasks for OpenStack communication
$string['task_openstack_async_communication']= 'Creación de servidores de conferencias BBB con OpenStack';

//Messages related with OpenStack
$string['messageprovider:openstack_conection_error'] = 'BigBlueButton: Notificacion de error de conexión con OpenStack' ;

/*---- end of OpenStack integration ----*/

$string['activityoverview'] = 'Tiene sesiones de Videocomunicación por comenzar';
$string['bbbduetimeoverstartingtime'] = 'El tiempo de cierre de esta viedoconferncia debe ser mayor al tiempo de incio';
$string['bbbdurationwarning'] = 'La duración máxima de esta videoconfrencia es de %duration% minutos.';
$string['bbbrecordwarning'] = 'Esta sesión puede estar siendo grabada';
$string['bigbluebuttonbn:join'] = 'Unirse a una videocomunicación';
$string['bigbluebuttonbn:moderate'] = 'Moderar una videocomunicación';
$string['bigbluebuttonbn:managerecordings'] = 'Administrar grabaciones';
$string['bigbluebuttonbn:addinstance'] = 'Agregar una videocomunicación';
$string['bigbluebuttonbn'] = 'Videocomunicación';

$string['config_general'] = 'Configuraciones generales';
$string['config_general_description'] = 'Estas configuraciones <b>siempre</b> se usan.';
$string['config_server_url'] = 'URL del servidor BigBlueButton';
$string['config_server_url_description'] = 'El URL del servidor de Bigbluebutton, debe terminar con /bigbluebutton/. (El URL por defecto de un servidor BigBlueButton es proveído por Blindside Networks, con el fin de realizar pruebas.)';
$string['config_shared_secret'] = '<i>Shared Secret</i> de BigBlueButton';
$string['config_shared_secret_description'] = 'El <i>shared secret</i> de seguridad del servidor de BigBlueButton.  (El <i>Shared Secret</i> por defecto de un servidor BigBlueButton es proveído por Blindside Networks, con el fin de realizar pruebas.)';

$string['config_feature_recording'] = 'Configuraciones  para "Grabaciones"';
$string['config_feature_recording_description'] = 'Estas son configuraciones específicas de esta funcionalidad.';
$string['config_feature_recording_default'] = 'Grabaciones habilitadas por defecto';
$string['config_feature_recording_default_description'] = 'Indica si las sesiones de BigBlueButton tendrán la funcionalidad de grabar por defecto.';
$string['config_feature_recording_editable'] = 'Editar Grabaciones';
$string['config_feature_recording_editable_description'] = 'Si está marcado la interfaz incluye una opción para habilitar y desabilitar la funcionalidad de grabar.';
$string['config_feature_recording_icons_enabled'] = 'Iconos para grabaciones';
$string['config_feature_recording_icons_enabled_description'] = 'Cuando está habilitado, el panel de administración de grabaciones muestra los iconos para las acciones de publicar o eliminar.';

$string['config_feature_recordingtagging'] = 'Configuraciones para "Marcado de etiquetas"';
$string['config_feature_recordingtagging_description'] = 'Estas configuraciones son específicas de esta funcionalidad';
$string['config_feature_recordingtagging_default'] = 'Marcado de grabación habilitado por defecto';
$string['config_feature_recordingtagging_default_description'] = 'La función de marcado de grabación está habilitada de forma predeterminada cuando se agrega una nueva sala o conferencia. <br> Cuando esta función está habilitada, se muestra una página intermedia que permite introducir una descripción y etiquetas para la sesión de la videocomunicación. Al primer moderador que se une. La descripción y las etiquetas se utilizan posteriormente para identificar la grabación en la lista.';
$string['config_feature_recordingtagging_editable'] = 'Habilitar edición de Marcado de etiquetas';
$string['config_feature_recordingtagging_editable_description'] = 'El valor de etiquetado de grabación se puede editar por defecto cuando se agrega o actualiza la sala o la conferencia.';

$string['config_feature_importrecordings'] = 'Configuración para "Importar grabaciones"';
$string['config_feature_importrecordings_description'] = 'Estas configuraciones son específicas de la función';
$string['config_feature_importrecordings_enabled'] = 'Importar grabaciones habilitadas';
$string['config_feature_importrecordings_enabled_description'] = 'Cuando está habilitado, es posible importar grabaciones de diferentes cursos en una actividad.';
$string['config_feature_importrecordings_from_deleted_activities_enabled'] = 'Importar grabaciones de actividades eliminadas';
$string['config_feature_importrecordings_from_deleted_activities_enabled_description'] = 'Cuando esta habilitado, es posible importar grabaciones de actividades que ya no están en el curso.';

$string['config_feature_waitformoderator'] = 'Configuración para "Esperar moderador" ';
$string['config_feature_waitformoderator_description'] = 'Estos ajustes son específicos de la función';
$string['config_feature_waitformoderator_default'] = 'Esperar moderador habilitado por defecto';
$string['config_feature_waitformoderator_default_description'] = 'La función de espera de moderador está habilitada de forma predeterminada cuando se agrega una nueva sala o conferencia.';
$string['config_feature_waitformoderator_editable'] = 'Habilitar edición de Esperar moderador';
$string['config_feature_waitformoderator_editable_description'] = 'Esperar moderador por defecto puede ser editado cuando se añade o actualiza la sala o la conferencia.';
$string['config_feature_waitformoderator_ping_interval'] = 'Esperar ping moderador (segundos)';
$string['config_feature_waitformoderator_ping_interval_description'] = 'Cuando la función Esperar moderator está habilitada, el cliente consulta mediante un <i>ping</i> el estado de la sesión cada [número] segundos. Este parámetro define el intervalo para las solicitudes hechas al servidor de Moodle.';
$string['config_feature_waitformoderator_cache_ttl'] = 'Esperar la caché del moderador TTL (segundos)';
$string['config_feature_waitformoderator_cache_ttl_description'] = 'Para soportar una gran carga de clientes, este plugin hace uso de una caché. Este parámetro define el tiempo que se guardará la memoria caché antes de enviar la siguiente solicitud al servidor BigBlueButton. ';

$string['config_feature_voicebridge'] = 'Configuración para "Puente de voz" ';
$string['config_feature_voicebridge_description'] = 'Esta configuración habilita o deshabilita las opciones en la interfaz de usuario y también define los valores por defecto para estas opciones.';
$string['config_feature_voicebridge_editable'] = 'Habilitar edición de Puente de voz';
$string['config_feature_voicebridge_editable_description'] = 'El número de puente de voz de la conferencia se puede asignar permanentemente a una conferencia de sala. Cuando se le asigna, el número no puede ser utilizado por ninguna otra sala o conferencia ';

$string['config_feature_preuploadpresentation'] = 'Configuración para "Pre-cargar presentación"';
$string['config_feature_preuploadpresentation_description'] = 'Esta configuración habilita o deshabilita las opciones en la interfaz de usuario y también define valores predeterminados para estas opciones. La función sólo funciona si el servidor Moodle es accesible a BigBlueButton .. ';
$string['config_feature_preuploadpresentation_enabled'] = 'Pre-cargar presentación habilitada';
$string['config_feature_preuploadpresentation_enabled_description'] = 'La función Pre-cargar presentación está habilitada en la interfaz de usuario cuando se agrega o actualiza la sala o la conferencia.';

$string['config_permission'] = 'Configuración de permisos';
$string['config_permission_description'] = 'Esta configuración define los permisos por defecto para las salas o conferencias creadas.';
$string['config_permission_moderator_default'] = 'Moderador por defecto';
$string['config_permission_moderator_default_description'] = 'Esta regla se usa por defecto cuando se agrega una nueva sala o conferencia.';

$string['config_feature_userlimit'] = 'Configuración para "Limitar usuario" ';
$string['config_feature_userlimit_description'] = 'Esta configuración habilita o deshabilita las opciones en la interfaz de usuario y también define valores por defecto para estas opciones.';
$string['config_feature_userlimit_default'] = 'Limitar usuario habilitado por defecto';
$string['config_feature_userlimit_default_description'] = 'El número de usuarios permitidos en una sesión por defecto cuando se agrega una nueva sala o conferencia. Si el número se establece en 0, no se establece ningún límite ';
$string['config_feature_userlimit_editable'] = 'Habilitar edición de Limitar usuario';
$string['config_feature_userlimit_editable_description'] = 'El valor límite del usuario se puede editar por defecto cuando se agrega o actualiza la sala o la conferencia.';

$string['config_scheduled'] = 'Configuración para "Sesiones programadas" ';
$string['config_scheduled_description'] = 'Esta configuración define algunos de los comportamientos por defecto para las sesiones programadas.';
$string['config_scheduled_duration_enabled'] = 'Calcular duración activada';
$string['config_scheduled_duration_enabled_description'] = 'La duración de una sesión programada se calcula en función de los horarios de apertura y cierre.';
$string['config_scheduled_duration_compensation'] = 'Tiempo compensatorio (minutos)';
$string['config_scheduled_duration_compensation_description'] = 'Minutos añadidos al cierre programado al calcular la duración.';
$string['config_scheduled_pre_opening'] = 'Accesible antes del tiempo de apertura (minutos)';
$string['config_scheduled_pre_opening_description'] = 'El tiempo en minutos para que la sesión sea accesible antes de que venza el horario de apertura de los horarios.';

$string['config_feature_sendnotifications'] = 'Configuración para "Enviar notificaciones"';
$string['config_feature_sendnotifications_description'] = 'Estas configuraciones habilitan o deshabilitan las opciones en la interfaz de usuario y también definen valores por defecto para estas opciones.';
$string['config_feature_sendnotifications_enabled'] = 'Habilitar Enviar notificaciones';
$string['config_feature_sendnotifications_enabled_description'] = 'La función Enviar notificaciones está habilitada en la interfaz de usuario cuando se agrega o actualiza la sala o la conferencia.';

$string['config_extended_capabilities'] = 'Configuración para "Capacidades extendidas"';
$string['config_extended_capabilities_description'] = 'Configuración para capacidades extendidas cuando el servidor BigBlueButton las ofrece.';
$string['config_extended_feature_uidelegation_enabled'] = 'Habilitar delegación de interfaz';
$string['config_extended_feature_uidelegation_enabled_description'] = 'Estas configuraciones habilitan o deshabilitan la delegación de UI al servidor BigBlueButton.';
$string['config_extended_feature_recordingready_enabled'] = 'Notificaciones al grabar listas activadas';
$string['config_extended_feature_recordingready_enabled_description'] = 'Notificaciones cuando la función de grabación lista está habilitada.';

$string['config_warning_curl_not_installed'] = 'Esta función requiere la extensión CURL para php instalado y habilitado. Los ajustes sólo serán accesibles si se cumple esta condición. ';

$string['general_error_unable_connect'] = 'No se puede conectar. Compruebe la URL del servidor BigBlueButton y compruebe si el servidor BigBlueButton se está ejecutando. ';

$string['index_confirm_end'] = '¿Desea terminar la clase virtual?';
$string['index_disabled'] = 'deshabilitado';
$string['index_enabled'] = 'habilitado';
$string['index_ending'] = 'Fin del aula virtual ... espere';
$string['index_error_checksum'] = 'Se ha producido un error de suma de comprobación. Asegúrese de haber introducido la sal correcta. ';
$string['index_error_forciblyended'] = 'No se puede unir a esta reunión porque se ha finalizado manualmente.';
$string['index_error_unable_display'] = 'No se pueden mostrar las reuniones. Compruebe la URL del servidor BigBlueButton y compruebe si el servidor BigBlueButton se está ejecutando. ';
$string['index_heading_actions'] = 'Acciones';
$string['index_heading_group'] = 'Grupo';
$string['index_heading_moderator'] = 'Moderadores';
$string['index_heading_name'] = 'Habitación';
$string['index_heading_recording'] = 'Grabación';
$string['index_heading_users'] = 'Usuarios';
$string['index_heading_viewer'] = 'Espectadores';
$string['index_heading'] = 'Habitaciones Videocomunicación';
$string['mod_form_block_general'] = 'Ajustes generales';
$string['mod_form_block_presentation'] = 'Contenido de la presentación';
$string['mod_form_block_participants'] = 'Participantes';
$string['mod_form_block_schedule'] = 'Calendario para la sesión';
$string['mod_form_block_record'] = 'Configuración de grabación';
$string['mod_form_field_openingtime'] = 'Hora de inicio';
$string['mod_form_field_closingtime'] = 'Cierre de ingreso';
$string['mod_form_field_openingtime_help'] = 'Hora de inicio para que los participantes entren a la conferencia.';
$string['mod_form_field_closingtime_help'] = 'Hora de cierre para que los participantes entren a la conferencia.';
$string['mod_form_field_intro'] = 'Descripción';
$string['mod_form_field_intro_help'] = 'Una breve descripción de la sala o la conferencia.';
$string['mod_form_field_duration_help'] = 'Establecer la duración de una reunión establecerá el tiempo máximo para que una reunión se mantenga viva antes de terminar la grabación';
$string['mod_form_field_duration'] = 'Duración';
$string['mod_form_field_userlimit'] = 'Límite de usuario';
$string['mod_form_field_userlimit_help'] = 'Límite máximo de usuarios permitido en una reunión. Si el límite se establece en 0, el número de usuarios será ilimitado. ';
$string['mod_form_field_name'] = 'Nombre del aula virtual';
$string['mod_form_field_room_name'] = 'Nombre de la sala';
$string['mod_form_field_conference_name'] = 'Nombre de la conferencia';
$string['mod_form_field_record'] = 'Se puede grabar la sesión';
$string['mod_form_field_voicebridge'] = 'Puente de voz [####]';
$string['mod_form_field_voicebridge_help'] = 'Número de conferencia de voz que los participantes entran para unirse a la conferencia de voz al usar el acceso telefónico. Se debe escribir un número entre 1 y 9999. Si el valor es 0, el número de puente vocal estático será ignorado y BigBlueButton generará un número aleatorio. Un número 7 precederá a los cuatro dígitos digitados ';
$string['mod_form_field_voicebridge_format_error'] = 'Error de formato. Debe introducir un número entre 1 y 9999. ';
$string['mod_form_field_voicebridge_notunique_error'] = 'No es un valor único. Este número está siendo utilizado por otra sala o conferencia. ';
$string['mod_form_field_recordingtagging'] = 'Activar la interfaz de etiquetado';
$string['mod_form_field_wait'] = 'Esperar moderador';
$string['mod_form_field_wait_help'] = 'Los espectadores deben esperar hasta que un moderador entre en la sesión antes de que puedan hacerlo';
$string['mod_form_field_welcome'] = 'Mensaje de bienvenida';
$string['mod_form_field_welcome_help'] = 'Reemplaza el mensaje predeterminado configurado para el servidor BigBlueButton. El mensaje puede incluir palabras clave (%% CONFNAME %%, %% DIALNUM %%, %% CONFNUM %%) que se sustituirán automáticamente y también etiquetas html como <b>...</b> or <i></i>';
$string['mod_form_field_welcome_default'] = '<br> Bienvenido a <b>%%CONFNAME%%</b>!<br><br>Para entender cómo funciona la herramienta de videocomunicación, consulte <a href = "evento: https://portafoliovirtual.ucr.ac.cr/index.php/nueva-mediacion-virtual/37-portafolio/mediacionvirtual/acordionmediacionvirtual/235-manuales-de-nuevas-herramientas"><u>los materiales</u></a>.<br><br>Para unirse con audio, haga clic en el icono del auricular (esquina superior izquierda).<b> Utilice un auricular para evitar causar ruido a otros. </b>';
$string['mod_form_field_participant_add'] = 'Añadir participante';
$string['mod_form_field_participant_list'] = 'Lista de participantes';
$string['mod_form_field_participant_list_type_all'] = 'Todos los usuarios se inscribieron';
$string['mod_form_field_participant_list_type_role'] = 'Función';
$string['mod_form_field_participant_list_type_user'] = 'Usuario';
$string['mod_form_field_participant_list_type_owner'] = 'Propietario';
$string['mod_form_field_participant_list_text_as'] = 'como';
$string['mod_form_field_participant_list_action_add'] = 'Añadir';
$string['mod_form_field_participant_list_action_remove'] = 'Eliminar';
$string['mod_form_field_participant_bbb_role_moderator'] = 'Moderador';
$string['mod_form_field_participant_bbb_role_viewer'] = 'Visor';
$string['mod_form_field_participant_role_unknown'] = 'Desconocido';
$string['mod_form_field_predefinedprofile'] = 'Perfil predefinido';
$string['mod_form_field_predefinedprofile_help'] = 'Perfil predefinido';
$string['mod_form_field_notification'] = 'Enviar notificación';
$string['mod_form_field_notification_help'] = 'Enviar una notificación a los usuarios inscritos para hacerles saber que esta actividad se ha creado o modificado';
$string['mod_form_field_notification_created_help'] = 'Enviar una notificación a los usuarios inscritos para hacerles saber que esta actividad se ha creado';
$string['mod_form_field_notification_modified_help'] = 'Enviar una notificación a los usuarios inscritos para hacerles saber que esta actividad se ha modificado';
$string['mod_form_field_notification_msg_created'] = 'creado';
$string['mod_form_field_notification_msg_modified'] = 'modificado';
$string['mod_form_field_notification_msg_at'] = 'en';

$string['modulename'] = 'Videocomunicación';
$string['modulenameplural'] = 'Videocomunicación';
$string['modulename_help'] = 'La herramienta de Videocomunicación le permite reservar desde la plataforma de aulas virtuales una sesión de comunicación sincrónica para posteriormente participar en tiempo real usando BigBlueButton, un sistema de conferencia web de código abierto para la educación a distancia.

Utilizando la herramienta de Videocomunicación puede especificar el título, la descripción, la fecha de acceso (que proporciona un intervalo de fechas para unirse a la sesión), los grupos y los detalles de la sesión en línea.';
$string['modulename_link'] = 'https://portafoliovirtual.ucr.ac.cr/index.php/nueva-mediacion-virtual/37-portafolio/mediacionvirtual/acordionmediacionvirtual/235-manuales-de-nuevas-herramientas';
$string['starts_at'] = 'Inicios';
$string['started_at'] = 'Iniciado';
$string['ends_at'] = 'Finaliza';
$string['pluginadministration'] = 'Administración de BigBlueButton';
$string['pluginname'] = 'Videocomunicación';
$string['serverhost'] = 'Nombre del servidor';
$string['view_error_no_group_student'] = 'No se ha inscrito en un grupo. Comuníquese con su Maestro o con el Administrador. ';
$string['view_error_no_group_teacher'] = 'No hay grupos configurados todavía. Por favor, configure grupos o póngase en contacto con el Administrador. ';
$string['view_error_no_group'] = 'No hay grupos configurados todavía. Por favor, configure grupos antes de intentar unirse a la reunión. ';
$string['view_error_unable_join_student'] = 'No se puede conectar al servidor BigBlueButton. Comuníquese con su Maestro o con el Administrador. ';
$string['view_error_unable_join_teacher'] = 'No se puede conectar al servidor BigBlueButton. Póngase en contacto con el Administrador. ';
$string['view_error_unable_join'] = 'No se puede unir a la reunión. Compruebe la URL del servidor BigBlueButton y compruebe si el servidor BigBlueButton se está ejecutando. ';
$string['view_error_bigbluebutton'] = 'BigBlueButton respondió con errores. {$ A} ';
$string['view_error_create'] = 'El servidor BigBlueButton respondió con un mensaje de error, no se pudo crear la reunión.';
$string['view_error_max_concurrent'] = 'Se ha alcanzado el número de reuniones concurrentes permitidas.';
$string['view_error_userlimit_reached'] = 'Se ha alcanzado el número de usuarios permitidos en una reunión.';
$string['view_error_url_missing_parameters'] = 'Hay parámetros que faltan en esta URL';
$string['view_error_import_no_courses'] = 'No hay cursos para buscar las grabaciones';
$string['view_error_import_no_recordings'] = 'No hay grabaciones en este curso para importar';
$string['view_groups_selection_join'] = 'Unirse';
$string['view_groups_selection'] = 'Seleccione el grupo al que desea unirse y confirme la acción';
$string['view_login_moderator'] = 'Iniciar sesión como moderador ...';
$string['view_login_viewer'] = 'Iniciar sesión como visor ...';
$string['view_noguests'] = 'El Videocomunicación no está abierto a invitados';
$string['view_nojoin'] = 'No puedes participar en esta sesión.';
$string['view_recording_list_actionbar_delete'] = 'Eliminar';
$string['view_recording_list_actionbar_deleting'] = 'Eliminar';
$string['view_recording_list_actionbar_hide'] = 'Ocultar';
$string['view_recording_list_actionbar_show'] = 'Mostrar';
$string['view_recording_list_actionbar_publish'] = 'Publicar';
$string['view_recording_list_actionbar_unpublish'] = 'No publicar';
$string['view_recording_list_actionbar_publishing'] = 'Publicación';
$string['view_recording_list_actionbar_unpublishing'] = 'No publicar';
$string['view_recording_list_actionbar_processing'] = 'Procesamiento';
$string['view_recording_list_actionbar'] = 'Barra de herramientas';
$string['view_recording_list_activity'] = 'Actividad';
$string['view_recording_list_course'] = 'Curso';
$string['view_recording_list_date'] = 'Fecha';
$string['view_recording_list_description'] = 'Descripción';
$string['view_recording_list_duration'] = 'Duración';
$string['view_recording_list_recording'] = 'Grabación';
$string['view_recording_button_import'] = 'Importar enlaces de grabación';
$string['view_recording_button_return'] = 'Regresar';
$string['view_recording_format_presentation'] = 'presentación';
$string['view_recording_format_video'] = 'video';
$string['view_section_title_presentation'] = 'Archivo de presentación';
$string['view_section_title_recordings'] = 'Grabaciones';
$string['view_message_norecordings'] = 'No hay grabación para esta reunión.';
$string['view_message_finished'] = 'Esta actividad ha terminado.';
$string['view_message_notavailableyet'] = 'Esta sesión aún no está disponible.';

$string['view_message_session_started_at'] = 'Esta sesión comenzó en';
$string['view_message_session_running_for'] = 'Esta sesión se ha estado ejecutando para';
$string['view_message_hour'] = 'hora';
$string['view_message_hours'] = 'horas';
$string['view_message_minute'] = 'minuto';
$string['view_message_minutes'] = 'minutos';
$string['view_message_moderator'] = 'moderador';
$string['view_message_moderators'] = 'moderadores';
$string['view_message_viewer'] = 'espectador';
$string['view_message_viewers'] = 'espectadores';
$string['view_message_user'] = 'usuario';
$string['view_message_users'] = 'usuarios';
$string['view_message_has_joined'] = 'se ha unido';
$string['view_message_have_joined'] = 'se han unido';
$string['view_message_session_no_users'] = 'No hay usuarios en esta sesión';
$string['view_message_session_has_user'] = 'Hay';
$string['view_message_session_has_users'] = 'Hay';

$string['view_message_room_closed'] = 'Esta habitación está cerrada.';
$string['view_message_room_ready'] = 'Esta sala está lista.';
$string['view_message_room_open'] = 'Esta sala está abierta.';
$string['view_message_conference_room_ready'] = 'Esta sala de conferencias está lista. Puede unirse a la sesión ahora. ';
$string['view_message_conference_not_started'] = 'Esta conferencia aún no ha comenzado.';
$string['view_message_conference_wait_for_moderator'] = 'Esperando un moderador para unirse.';
$string['view_message_conference_in_progress'] = 'Esta conferencia está en curso.';
$string['view_message_conference_has_ended'] = 'Esta conferencia ha terminado.';
$string['view_message_tab_close'] = 'Esta pestaña / ventana debe cerrarse manualmente';

$string['view_groups_selection_warning'] = 'Hay una sala de conferencias para cada grupo. Si tiene acceso a más de uno, asegúrese de seleccionar el correcto. ';
// $ string ['view_groups_selection_message'] = 'Seleccione el grupo al que desea participar.';
// $ string ['view_groups_selection_button'] = 'Seleccionar';
$string['view_conference_action_join'] = 'Unirse a la sesión';
$string['view_conference_action_end'] = 'Finalizar sesión';

$string['view_recording'] = 'grabación';
$string['view_recording_link'] = 'enlace importado';
$string['view_recording_link_warning'] = 'Este es un enlace que apunta a una grabación que se creó en un curso o actividad diferente';
$string['view_recording_delete_confirmation'] = '¿Está seguro de borrar este {$a}?';
$string['view_recording_delete_confirmation_warning_s'] = 'Esta grabación tiene {$a} enlace asociado que fue importado en un curso o actividad diferente. Si se elimina la grabación, el enlace también se eliminará ';
$string['view_recording_delete_confirmation_warning_p'] = 'Esta grabación tiene {$a} enlaces asociados que fueron importados en diferentes cursos o actividades. Si la grabación se elimina, esos enlaces también serán eliminados ';
$string['view_recording_publish_link_error'] = 'Este enlace no se puede volver a publicar porque la grabación física es inédita';
$string['view_recording_unpublish_confirmation'] = '¿Está seguro de anular la publicación de este {$a}?';
$string['view_recording_unpublish_confirmation_warning_s'] = 'Esta grabación tiene {$a} enlace asociado que fue importado en un curso o actividad diferente. Si la grabación no se publica, el enlace también será inédito ';
$string['view_recording_unpublish_confirmation_warning_p'] = 'Esta grabación tiene {$a} enlaces asociados que fueron importados en diferentes cursos o actividades. Si la grabación no se publica, esos enlaces también serán inéditos ';
$string['view_recording_import_confirmation'] = '¿Está seguro de importar esta grabación?';
$string['view_recording_actionbar'] = 'Barra de herramientas';
$string['view_recording_activity'] = 'Actividad';
$string['view_recording_course'] = 'Curso';
$string['view_recording_date'] = 'Fecha';
$string['view_recording_description'] = 'Descripción';
$string['view_recording_length'] = 'Longitud';
$string['view_recording_duration'] = 'Duración';
$string['view_recording_recording'] = 'Grabación';
$string['view_recording_duration_min'] = 'min';
$string['view_recording_name'] = 'Nombre';
$string['view_recording_tags'] = 'Etiquetas';
$string['view_recording_modal_button'] = 'Aplicar';
$string['view_recording_modal_title'] = 'Establecer valores para la grabación';

$string['event_activity_created'] = 'Actividad de Videocomunicación creada';
$string['event_activity_deleted'] = 'Actividad de Videocomunicación eliminada';
$string['event_activity_modified'] = 'Actividad Videocomunicación modificada';
$string['event_activity_viewed'] = 'Actividad de Videocomunicación vista';
$string['event_activity_viewed_all'] = 'Administración de actividades de Videocomunicación vista';
$string['event_meeting_created'] = 'Creación de la reunión de Videocomunicación';
$string['event_meeting_ended'] = 'Reunión de Videocomunicación terminada por la fuerza';
$string['event_meeting_joined'] = 'Reunión Videocomunicación unida';
$string['event_meeting_left'] = 'Videocomunicación se encuentra a la izquierda';
$string['event_recording_deleted'] = 'Grabación borrada';
$string['event_recording_imported'] = 'Grabación importada';
$string['event_recording_published'] = 'Grabación publicada';
$string['event_recording_unpublished'] = 'Grabación no publicada';

$string['predefined_profile_default'] = 'Predeterminado';
$string['predefined_profile_classroom'] = 'Aula';
$string['predefined_profile_conferenceroom'] = 'Sala de conferencias';
$string['predefined_profile_collaborationroom'] = 'Sala de colaboración';
$string['predefined_profile_scheduledsession'] = 'Sesión programada';

$string['email_title_notification_has_been'] = 'ha sido';
$string['email_body_notification_meeting_has_been'] = 'ha sido';
$string['email_body_notification_meeting_details'] = 'Detalles';
$string['email_body_notification_meeting_title'] = 'Título';
$string['email_body_notification_meeting_description'] = 'Descripción';
$string['email_body_notification_meeting_start_date'] = 'Fecha de inicio';
$string['email_body_notification_meeting_end_date'] = 'Hora de cierre de ingreso';
$string['email_body_notification_meeting_by'] = 'por';
$string['email_body_recording_ready_for'] = 'Grabación para';
$string['email_body_recording_ready_is_ready'] = 'está listo';
$string['email_footer_sent_by'] = 'Este mensaje de notificación automática fue enviado por';
$string['email_footer_sent_from'] = 'del curso';
