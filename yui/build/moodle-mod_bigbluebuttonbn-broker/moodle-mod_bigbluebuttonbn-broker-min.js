YUI.add("moodle-mod_bigbluebuttonbn-broker",function(e,t){M.mod_bigbluebuttonbn=M.mod_bigbluebuttonbn||{},M.mod_bigbluebuttonbn.broker={data_source:null,polling:null,bigbluebuttonbn:{},panel:null,init:function(t){this.data_source=new e.DataSource.Get({source:M.cfg.wwwroot+"/mod/bigbluebuttonbn/bbb_broker.php?"}),this.bigbluebuttonbn=t},waitModerator:function(){var t=e.one("#status_bar_span"),n=e.DOM.create("<img>");e.DOM.setAttribute(n,"id","spinning_wheel"),e.DOM.setAttribute(n,"src","pix/processing16.gif"),e.DOM.addHTML(t,"&nbsp;"),e.DOM.addHTML(t,n);var r="action=meeting_info";r+="&id="+this.bigbluebuttonbn.meetingid,r+="&bigbluebuttonbn="+this.bigbluebuttonbn.bigbluebuttonbnid,this.polling=this.data_source.setInterval(this.bigbluebuttonbn.ping_interval,{request:r,callback:{success:function(e){e.data.running&&(clearInterval(this.polling),M.mod_bigbluebuttonbn.rooms.clean_room(),M.mod_bigbluebuttonbn.rooms.update_room())},failure:function(){clearInterval(this.polling)}}})},join:function(t,n,r){var i="";if(!r){M.mod_bigbluebuttonbn.broker.joinRedirect(t);return}e.one("#panelContent").removeClass("hidden"),i+="action=meeting_info",i+="&id="+this.bigbluebuttonbn.meetingid,i+="&bigbluebuttonbn="+this.bigbluebuttonbn.bigbluebuttonbnid,this.data_source.sendRequest({request:i,callback:{success:function(n){if(!n.data.running){e.one("#meeting_join_url").set("value",t),e.one("#meeting_message").set("value",n.data.status.message),YUI({lang:this.bigbluebuttonbn.locale}).use("panel",function(){this.panel.show()});return}M.mod_bigbluebuttonbn.broker.joinRedirect(t,n.data.status.message)}}})},joinRedirect:function(e){window.open(e),setTimeout(function(){M.mod_bigbluebuttonbn.rooms.clean_room(),M.mod_bigbluebuttonbn.rooms.update_room()},15e3)},recordingAction:function(e,t,n){if(e==="import"){this.recordingImport(t);return}if(e==="delete"){this.recordingDelete(t);return}if(e==="publish"){this.recordingPublish(t,n);return}if(e==="unpublish"){this.recordingUnpublish(t,n);return}},recordingImport:function(t){var n=new M.core.confirm({modal:!0,centered:!0,question:this.recordingConfirmationMessage("import",t)});n.on("complete-yes",function(){this.data_source.sendRequest({request:"action=recording_import&id="+t,callback:{success:function(){e.one("#recording-td-"+t).remove()}}})},this)},recordingDelete:function(t){var n=new M.core.confirm({modal:!0,centered:!0,question:this.recordingConfirmationMessage("delete",t)});n.on("complete-yes",function(){this.data_source.sendRequest({request:"action=recording_delete&id="+t,callback:{success:function(){e.one("#recording-td-"+t).remove()}}})},this)},recordingPublish:function(e,t){var n=this.data_source,r=this.ping_interval,i=this.polling;this.data_source.sendRequest({request:"action=recording_publish&id="+e,callback:{success:function(s){if(s.data.status==="true"){var o={action:"publish",meetingid:t,recordingid:e};i=n.setInterval(r,M.mod_bigbluebuttonbn.broker.pingRecordingObject(o))}else{var u=new M.core.alert({message:s.data.message});u.show()}}}})},recordingUnpublish:function(e,t){var n=new M.core.confirm({modal:!0,centered:!0,question:this.recordingConfirmationMessage("unpublish",e)}),r=this.data_source,i=this.ping_interval,s=this.polling;n.on("complete-yes",function(){r.sendRequest({request:"action=recording_unpublish&id="+e,callback:{success:function(n){if(n.data.status==="true"){var o={action:"unpublish",meetingid:t,recordingid:e};s=r.setInterval(i,M.mod_bigbluebuttonbn.broker.pingRecordingObject(o))}else{var u=new M.core.alert({message:n.data.message});u.show()}}}})},this)},recordingConfirmationMessage:function(t,n){if(M.mod_bigbluebuttonbn.locales.strings[t+"_confirmation"]==="undefined")return"";var r=e.one("#playbacks-"+n).get("dataset").imported==="true",i=M.mod_bigbluebuttonbn.locales.strings.recording;r&&(i=M.mod_bigbluebuttonbn.locales.strings.recording_link);var s=M.mod_bigbluebuttonbn.locales.strings[t+"_confirmation"];s=s.replace("{$a}",i);if(t==="publish"||t==="delete"){var o=e.one("#recording-link-"+t+"-"+n).get("dataset").links,u=M.mod_bigbluebuttonbn.locales.strings[t+"_confirmation_warning_p"];o==1&&(u=M.mod_bigbluebuttonbn.locales.strings[t+"_confirmation_warning_s"]),u=u.replace("{$a}",o)+". ",s=u+"\n\n"+s}return s},pingRecordingObject:function(t){var n=e.one("#recording-btn-"+t.action+"-"+t.recordingid),r=n.getAttribute("src"),i=r.substring(0,r.length-4);n.setAttribute("src",M.cfg.wwwroot+"/mod/bigbluebuttonbn/pix/processing16.gif"),t.action=="publish"?(n.setAttribute("alt",M.mod_bigbluebuttonbn.locales.strings.publishing),n.setAttribute("title",M.mod_bigbluebuttonbn.locales.strings.publishing)):(n.setAttribute("alt",M.mod_bigbluebuttonbn.locales.strings.unpublishing),n.setAttribute("title",M.mod_bigbluebuttonbn.locales.strings.unpublishing));var s=e.one("#recording-link-"+t.action+"-"+t.recordingid),o=s.getAttribute("onclick");return s.setAttribute("onclick",""),{request:"action=recording_info&id="+t.recordingid+"&idx="+t.meetingid,callback:{success:function(r){if(r.data.status!=="true"){clearInterval(this.polling);return}if(t.action==="publish"&&r.data.published==="true"){clearInterval(this.polling),n.setAttribute("id","recording-btn-unpublish-"+t.recordingid),s.setAttribute("id","recording-link-unpublish-"+t.recordingid),n.setAttribute("src",i+"hide"),n.setAttribute("alt",M.mod_bigbluebuttonbn.locales.strings.unpublish),n.setAttribute("title",M.mod_bigbluebuttonbn.locales.strings.unpublish),s.setAttribute("onclick",o.replace("publish","unpublish")),e.one("#playbacks-"+t.recordingid).show();return}t.action==="unpublish"&&r.data.published==="false"&&(clearInterval(this.polling),n.setAttribute("id","recording-btn-publish-"+t.recordingid),s.setAttribute("id","recording-link-publish-"+t.recordingid),n.setAttribute("src",i+"show"),n.setAttribute("alt",M.mod_bigbluebuttonbn.locales.strings.publish),n.setAttribute("title",M.mod_bigbluebuttonbn.locales.strings.publish),s.setAttribute("onclick",o.replace("unpublish","publish")),e.one("#playbacks-"+t.recordingid
).hide())},failure:function(){clearInterval(this.polling)}}}},endMeeting:function(){var e="action=meeting_end&id="+this.bigbluebuttonbn.meetingid;e+="&bigbluebuttonbn="+this.bigbluebuttonbn.bigbluebuttonbnid,this.data_source.sendRequest({request:e,callback:{success:function(e){e.data.status&&(M.mod_bigbluebuttonbn.rooms.clean_control_panel(),M.mod_bigbluebuttonbn.rooms.hide_join_button(),M.mod_bigbluebuttonbn.rooms.hide_end_button(),location.reload())}}})}}},"@VERSION@",{requires:["base","node","datasource-get","datasource-jsonschema","datasource-polling","moodle-core-notification"]});
