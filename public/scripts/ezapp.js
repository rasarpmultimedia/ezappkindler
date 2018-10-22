/**
* This software was developed by Rasarp Multimedia Inc
* @namespace :ezappkindiler
* @Author  : Sarpong Abdul-Rahman Dugbatey
* @Version : v1.4.1
* @Licence : GPL v3.2
* Release Date: 07-09-2018
**/

(function() {
 let ezapp = {};
/* Document Objects Helper Methods */
ezapp.$ = {};

ezapp.fetchById = function fetchById(id){
     return document.getElementById(id);
};
ezapp.fetchTagName = function fetchTagName(tagName){ 
    return document.getElementsByTagName (tagName);
};

ezapp.queryAll= function queryAll(nodeList){ 
    return document.querySelectorAll(nodeList);
};
ezapp.querySelect = function querySelect(element){
    return document.querySelector(element);
};

/*Data API*/
 ezapp.data={
     parse: function parse(data){ return JSON.parse(data); },
     stringify:function stringify(data){ return JSON.stringify(data); }
};
/** FORM API **/
ezapp.$.FORM = {
    validate: {},
    field: function field(id){ return ezapp.fetchById(id);},
    fieldValue: function fieldValue(id){ return ezapp.fetchById(id).value;},
    formData: new FormData,
    escapeval : function escapeval(val){return encodeURIComponent(val)},
    forms: document.forms
};
/* Document Objects Helper Methods Ends */	
/** Ajax Starts **/
ezapp.ajax = {type:"",entype:"",url:"",data:[]};//ezapp Ajax Object

let xhreq = null; 

ezapp.ajax.xhr = function(){    
    if(window.XMLHttpRequest){
        xhreq = new XMLHttpRequest;
    }else{
        xhreq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return xhreq;
};

xhreq = ezapp.ajax.xhr();
//Ajax Pagination
ezapp.paginate = {
    currentpage:1,
    limit_perpage:10,
    totalPages:20,
    loadPagination: function(pagenum){}
};
//Ajax Event Handler

ezapp.ajax.$_response = function(callback){
     xhreq.onreadystatechange = function(){
        if(xhreq.readyState===4 && xhreq.status===200){
          callback();  
        }
    };
    return true;
};
ezapp.ajax.responseData = function(){};
/* Ajax Get */
ezapp.ajax.$_get = function(url){
    url = url!==null?url:ezapp.ajax.url;
    xhreq.open("GET",url,true);
    xhreq.send(null);   
    return true;
};

/* Ajax Post */
ezapp.ajax.$_post = function(url,data){
    url = (url!==null?url:ezapp.ajax.url);
    data= (data!==null?data:ezapp.ajax.data);
    xhreq.open("POST",url,true);//application/x-www-form-urlencoded
    ezapp.ajax.entype == null?"application/x-www-form-urlencoded":ezapp.ajax.entype;
    xhreq.setRequestHeader("Content-type",ezapp.ajax.entype);
    xhreq.send(data.join("&"));
    return true;
};

/**
ezapp.ajax.$_submit(obj);
**/
ezapp.obj = {url:"",type:"",data:[],entype:""};
ezapp.ajax.$_submit=function(obj){    
    if(obj.type==="GET"){
        xhreq.open(obj.type, obj.url+"?"+obj.data.join("&"),true);
        xhreq.send(null);	
    }else if(obj.type==="POST"){
        xhreq.open(obj.type,obj.url,true);
        xhreq.setRequestHeader("Content-type", obj.entype);
        xhreq.send(obj.data.join("&"));
    }
    return true;
};
/** Ajax Ends **/
/** Event Handler **/

ezapp.addEvent = function(doc,type,handler){
    if(!doc){doc = document;}
    if(doc.addEventListener){
       doc.addEventListener(type,handler,false); 
    }else if(doc.attachEvent){
       doc.attachEvent("on"+type,handler);
    }
};
	
ezapp.eventHandler = function(e){
     e = e || window.event;
    let target = e.target || e.srcElement;
    return target;
};
/** Form Validation Rules **/

//Single Line Text Field
ezapp.$.FORM.validate.isValid = false;
    ezapp.$.FORM.validate.textField = function(id,errormsg){
     let field = ezapp.fetchById(id);
     let error = ezapp.fetchById(id+"_error");
             field = (field!=null)?field:"";error = (error!=null)?error:"";
     let pattern = /[a-z ]/i;
        if(field.value == ""){
         return error.innerHTML = errormsg;
        }else if(!pattern.test(field.value)){
        ezapp.$.FORM.validate.isValid = false;
        return error.innerHTML = "Invalid "+field.value+ ", is not allowed";
        }else{
        ezapp.$.FORM.validate.isValid = true;
        return error.innerHTML = "";
        }
        return false;
    };		 
    ezapp.$.FORM.validate.digit = function(id,errormsg){
     let field = ezapp.fetchById(id);
     let error = ezapp.fetchById(id+"_error");
             field = (field!=null)?field:"";error = (error!=null)?error:"";
     let pattern = /^\d+$/i;
            if(field.value == ""){
              ezapp.$.FORM.validate.isValid = false;
              return error.innerHTML = errormsg;
            }else if(!pattern.test(field.value)){
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = "Invalid digit "+field.value+", is not allowed";
            }else{
             ezapp.$.FORM.validate.isValid = true;
             return error.innerHTML = "";	
            }
            return false;
    };
    ezapp.$.FORM.validate.currency = function(id,errormsg){
     let field = ezapp.fetchById(id);
     let error = ezapp.fetchById(id+"_error");
             field = (field!=null)?field:"";error = (error!=null)?error:"";
            let pattern = /^[0-9]*(\.[0-9]+)?$/;

            if(field.value == ""){
             ezapp.$.FORM.validate.isValid = false;	
             return error.innerHTML = errormsg;
            }else if(!pattern.test(field.value)){
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = "Invalid currency "+field.value+", is not allowed";
            }else{
             ezapp.$.FORM.validate.isValid = true;
             return error.innerHTML = "";	
            }
            return false;
    };
    //Text Area Field or Multi Line Text
    ezapp.$.FORM.validate.textAreaField = function(id,errormsg){
     let field = ezapp.fetchById(id);
     let error = ezapp.fetchById(id+"_error");
             field = (field!=null)?field:"";error = (error!=null)?error:"";
     let pattern = /^.+$/i;
            if(field.value == ""){
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = errormsg;
            }else if(!pattern.test(field.value)){
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = "Invalid "+field.value+ ", is not allowed";
            }else{
             ezapp.$.FORM.validate.isValid = true;
             return error.innerHTML = "";
            }
            return false;
    };
    //Email
    ezapp.$.FORM.validate.emailField = function(id,errormsg){
     let field = ezapp.fetchById(id);
     let error = ezapp.fetchById(id+"_error");
             field = (field!=null)?field:"";error = (error!=null)?error:"";
     let pattern = /^[\w!#$%&'*+/=?`{|}~^-]+(?:\.[\w!#$%&'*+/=?`{|}~^-]+)*@(?:[A-Z0-9-]+\.)+[A-Z]{2,6}$/i;
            if(field.value == ""){
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = errormsg;
            }else if(!pattern.test(field.value)){
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = "Invalid "+field.value+ ", is not an email address";	
            }else{
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = "";	
            }
            return false;
    };
    //Password
    ezapp.$.FORM.validate.passwordField = function(id,errormsg){
     let field = ezapp.fetchById(id);
     let error = ezapp.fetchById(id+"_error");
             field = (field!=null)?field:"";error = (error!=null)?error:"";
     let pattern = /^\w+$/i;
            if(field.value == ""){
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = errormsg;
            }else if(!pattern.test(field.value)){
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = "Invalid "+field.value+", is not allowed";
            }else{
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = "";
            }
            return false;
    };
    ezapp.$.FORM.validate.passwordMatchField = function(matchval,id,errormsg){
     let field = ezapp.fetchById(id);
     let error = ezapp.fetchById(id+"_error");
             field = (field!=null)?field:"";error = (error!=null)?error:"";
     let pattern = /matchval/i;
            if(!pattern.test(field.value)){
             ezapp.$.FORM.validate.isValid = false;
             return error.innerHTML = errormsg;
            }else{			
            ezapp.$.FORM.validate.isValid = false;
            return error.innerHTML = "";
            }
     return false;
    };
window.ezapp = ezapp;
return window.ezapp;	
})();

/** ezappkindiler application **/