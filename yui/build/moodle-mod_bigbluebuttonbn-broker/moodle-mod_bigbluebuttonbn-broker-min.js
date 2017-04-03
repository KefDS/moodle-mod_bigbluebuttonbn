YUI.add("moodle-mod_bigbluebuttonbn-broker",function(e,t){M.mod_bigbluebuttonbn=M.mod_bigbluebuttonbn||{},M.mod_bigbluebuttonbn.broker={datasource:null,bigbluebuttonbn:{},init:function(t){this.datasource=new e.DataSource.Get({source:M.cfg.wwwroot+"/mod/bigbluebuttonbn/bbb_broker.php?"}),this.bigbluebuttonbn=t},join:function(e){M.mod_bigbluebuttonbn.broker.join_redirect(e)},join_redirect:function(e){window.open(e),setTimeout(function(){M.mod_bigbluebuttonbn.rooms.clean_room(),M.mod_bigbluebuttonbn.rooms.update_room(!0)},15e3)},recording_action:function(e,t,n){return e==="import"?this.recording_import({recordingid:t}):e==="delete"?this.recording_delete({recordingid:t,meetingid:n}):e==="publish"?this.recording_publish({recordingid:t,meetingid:n}):e==="unpublish"?this.recording_unpublish({recordingid:t,meetingid:n}):e==="update"?this.recording_update({recordingid:t,meetingid:n}):null},recording_import:function(t){var n=new M.core.confirm({modal:!0,centered:!0,question:this.recording_confirmation_message("import",t)});n.on("complete-yes",function(){this.datasource.sendRequest({request:"action=recording_import&id="+t,callback:{success:function(){e.one("#recording-td-"+t).remove()}}})},this)},recording_delete:function(e,t){var n=new M.core.confirm({modal:!0,centered:!0,question:this.recording_confirmation_message("delete",e)});n.on("complete-yes",function(){this.recording_action_perform({action:"delete",recordingid:e,meetingid:t,goalstate:!1})},this)},recording_publish:function(e,t){this.recording_action_perform({action:"publish",recordingid:e,meetingid:t,goalstate:!0})},recording_unpublish:function(e,t){var n=new M.core.confirm({modal:!0,centered:!0,question:this.recording_confirmation_message("unpublish",e)});n.on("complete-yes",function(){this.recording_action_perform({action:"unpublish",recordingid:e,meetingid:t,goalstate:!1})},this)},recording_update:function(e,t){console.info("Updating...")},recording_action_perform:function(e){M.mod_bigbluebuttonbn.recordings.recording_action_inprocess(e),this.datasource.sendRequest({request:"action=recording_"+e.action+"&id="+e.recordingid,callback:{success:function(t){return t.data.status?M.mod_bigbluebuttonbn.broker.recording_action_performed({attempt:1,action:e.action,meetingid:e.meetingid,recordingid:e.recordingid,goalstate:e.goalstate}):(e.message=t.data.message,M.mod_bigbluebuttonbn.recordings.recording_action_failed(e))},failure:function(t){return e.message=t.error.message,M.mod_bigbluebuttonbn.recordings.recording_action_failed(e)}}})},recording_action_performed:function(e){this.datasource.sendRequest({request:"action=recording_info&id="+e.recordingid+"&idx="+e.meetingid,callback:{success:function(t){var n=M.mod_bigbluebuttonbn.broker.recording_current_state(e.action,t.data);return n===null?(e.message=M.util.get_string("view_error_current_state_not_found","bigbluebuttonbn"),M.mod_bigbluebuttonbn.recordings.recording_action_failed(e)):n===e.goalstate?M.mod_bigbluebuttonbn.recordings.recording_action_completed(e):e.attempt<5?(e.attempt+=1,setTimeout(function(){return function(){M.mod_bigbluebuttonbn.broker.recording_action_performed(e)}}(this),(e.attempt-1)*1e3)):(e.message=M.util.get_string("view_error_action_not_completed","bigbluebuttonbn"),M.mod_bigbluebuttonbn.recordings.recording_action_failed(e))},failure:function(t){return e.message=t.error.message,M.mod_bigbluebuttonbn.recordings.recording_action_failed(e)}}})},recording_current_state:function(e,t){return e==="publish"||e==="unpublish"?t.published:e==="delete"?t.status:e==="protect"||e==="unprotect"?t.secured:e==="update"?t.updated:null},recording_confirmation_message:function(t,n){var r=M.util.get_string("view_recording_"+t+"_confirmation","bigbluebuttonbn");if(r==="undefined")return"";var i=e.one("#playbacks-"+n).get("dataset").imported==="true",s=M.util.get_string("view_recording","bigbluebuttonbn");i&&(s=M.util.get_string("view_recording_link","bigbluebuttonbn")),r=r.replace("{$a}",s);if(t==="publish"||t==="delete"){var o=e.one("#recording-link-"+t+"-"+n).get("dataset").links,u=M.util.get_string("view_recording_"+t+"_confirmation_warning_p","bigbluebuttonbn");o==1&&(u=M.util.get_string("view_recording_"+t+"_confirmation_warning_s","bigbluebuttonbn")),u=u.replace("{$a}",o)+". ",r=u+"\n\n"+r}return r},end_meeting:function(){var e="action=meeting_end&id="+this.bigbluebuttonbn.meetingid;e+="&bigbluebuttonbn="+this.bigbluebuttonbn.bigbluebuttonbnid,this.datasource.sendRequest({request:e,callback:{success:function(e){e.data.status&&(M.mod_bigbluebuttonbn.rooms.clean_control_panel(),M.mod_bigbluebuttonbn.rooms.hide_join_button(),M.mod_bigbluebuttonbn.rooms.hide_end_button(),location.reload())}}})}}},"@VERSION@",{requires:["base","node","datasource-get","datasource-jsonschema","datasource-polling","moodle-core-notification"]});
