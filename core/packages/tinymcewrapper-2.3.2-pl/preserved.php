<?php return array (
  'cb0fc8ef669ccf90f5c9e738f6f033f9' => 
  array (
    'criteria' => 
    array (
      'name' => 'tinymcewrapper',
    ),
    'object' => 
    array (
      'name' => 'tinymcewrapper',
      'path' => '{core_path}components/tinymcewrapper/',
      'assets_path' => '{assets_path}components/tinymcewrapper/',
    ),
  ),
  '7364ec2458975a5dd3bf05c0dec527b3' => 
  array (
    'criteria' => 
    array (
      'category' => 'TinymceWrapper',
    ),
    'object' => 
    array (
      'id' => 1,
      'parent' => 0,
      'category' => 'TinymceWrapper',
      'rank' => 0,
    ),
  ),
  '45043481bcf1e7e249dbcca6c10d2dd7' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapper',
    ),
    'object' => 
    array (
      'id' => 1,
      'source' => 0,
      'property_preprocess' => 1,
      'name' => 'TinymceWrapper',
      'description' => 'Survive upgrades! Duplicate the Chunks, retain their original names with an added SUFFIX. Create dedicated PropertySet properties. Default properties will be overridden.
Include no script tags in any of the chunks.
Please be awesome!',
      'editor_type' => 0,
      'category' => 1,
      'cache_type' => 0,
      'plugincode' => '/*TinymceWrapper transforms all MODX native and non-native textareas (introtext, description, content, RichTVs, File/Image TVs, Quick Update/Create, MIGX TVs etc etc)

plugin fires at
OnRichTextEditorRegister
OnDocFormPrerender 

(optionally)
OnTempFormPrerender,OnSnipFormPrerender,OnChunkFormPrerender,OnPluginFormPrerender,OnFileEditFormPrerender

OnManagerPageInit
  FOR ALL ROUND ENJOYMENT THROUGH OUT THE MANAGER
  1. for (quick update/create resources & elements (for Ace or CodeMirror),
  2. for top nav media link for elFinder, Responsive FileManager and Roxy custom file browsers,


-------------------Roadmap:
-Create more beautiful themes;
-Look for more ways to be awesome;
---------------------------

Dedicated to those who have cried
---------------------------

http://www.leofec.com/modx-revolution/
-donshakespeare in the MODx forum
To God, almighty, be all the glory.
*/

$modx->getService(\'error\',\'error.modError\', \'\', \'\');
$modxEventName = $modx->event->name;
//let us tell System Settings that we have a new RTEditor
if ($modxEventName == \'OnRichTextEditorRegister\') {
  $modx->event->output(\'TinymceWrapper\');
  return;
}

//let us get MODx browser callback ready to fire
if ($modxEventName == \'OnRichTextBrowserInit\' && $autoFileBrowser == \'modxNativeBrowser\') {
 $modx->controller->addJavascript(MODX_ASSETS_URL.\'components/tinymcewrapper/browserConnectors/browser.js\');
  $modx->event->output(\'twBrowserCallback\');
  return;
}

//whether the user has RTE enabled in System Settings
$useEditor = $modx->getOption(\'use_editor\');
//is our awesome wrapper the set one?
$whichEditor = $modx->getOption(\'which_editor\');
$whichElementEditor = $modx->getOption(\'which_element_editor\');
//whether the user has RTE set to default for all resources in System Settings
$richtext_default = $modx->getOption(\'richtext_default\');

$sp = $scriptProperties;
//let\'s grab a few things from our plugin\'s defualt properties property set
$activate = $modx->getOption(\'activateTinyMCE\', $sp);
$activateAceOrCodeMirror = $modx->getOption(\'activateAceOrCodeMirror\', $sp);
$useAceOrCodeMirrorEveryWhere = $modx->getOption(\'useAceOrCodeMirrorEveryWhere\', $sp);
$useAceOrCodeMirrorOnElementsFiles = $modx->getOption(\'useAceOrCodeMirrorOnElementsFiles\', $sp);
$useAceOrCodeMirrorOnResources = $modx->getOption(\'useAceOrCodeMirrorOnResources\', $sp);
$activateAceOrCodeMirrorOnNewResource = $modx->getOption(\'activateAceOrCodeMirrorOnNewResource\', $sp);
$activateAceOrCodeMirrorOnRichText = $modx->getOption(\'activateAceOrCodeMirrorOnRichText\', $sp);
$AceTHEME = $modx->getOption(\'AceTHEME\', $sp);
$AceSrc = $modx->getOption(\'AceSrc\', $sp);
$CodeMirrorTHEME = $modx->getOption(\'CodeMirrorTHEME\', $sp);
$CodeMirrorSrc = $modx->getOption(\'CodeMirrorSrc\', $sp);
$jQuerySrc = $modx->getOption(\'jQuery\', $sp);
$tinySrc = $modx->getOption(\'tinySrc\', $sp);
$newResources = $modx->getOption(\'newResources\', $sp);
$introtext = $modx->getOption(\'Introtext\', $sp);
$intro = \'\';
$description = $modx->getOption(\'Description\', $sp);
$desc = \'\';
$content = $modx->getOption(\'Content\', $sp);
$con = \'\';
$tvs = $modx->getOption(\'TVs\', $sp);
$tvAddict = $modx->getOption(\'tvAddict\', $sp);
$tvSuperAddict = $modx->getOption(\'tvSuperAddict\', $sp);
$autoFileBrowser = $modx->getOption(\'autoFileBrowser\', $sp);
$browserTopNAVsubtext = $modx->getOption(\'browserTopNAVsubtext\', $sp);
$fileImageTVs = $modx->getOption(\'fileImageTVs\', $sp);
$browserTVs = \'\';
$disable = $modx->getOption(\'disableEnableCheckbox\', $sp);
//if a suffix is entered, all the chunks in use must have the same suffix. (e.g. TinymceWrapperIntrotext-suff, TinymceWrapperDescription-suff,TinymceWrapperContent-suff,TinymceWrapperTvs-suff)
$suffix = $modx->getOption(\'chunkSuffix\', $sp);
$fileManagerTopNavLink = $modx->getOption(\'fileManagerTopNavLink\', $sp);
$fileManagerTopNavModal = $modx->getOption(\'fileManagerTopNavModal\', $sp);
$fileManagerTopNavModalSkin = $modx->getOption(\'fileManagerTopNavModalSkin\', $sp);
$fileManagerTopNavModalSkin = $fileManagerTopNavModalSkin ? : \'""\';

//grab file browser options
$modxNativeBrowserRTEurl = $modx->getOption(\'modxNativeBrowserRTEurl\', $sp);
$modxNativeBrowserRTEtitle = $modx->getOption(\'modxNativeBrowserRTEtitle\', $sp);
$modxNativeBrowserTopNAVpresent = $modx->getOption(\'modxNativeBrowserTopNAVpresent\', $sp);
$modxNativeBrowserQuirkMode = $modx->getOption(\'modxNativeBrowserQuirkMode\', $sp);

$replaceDefaultFileImageTVbutton = $modx->getOption(\'replaceDefaultFileImageTVbutton\', $sp) ? : 0;

$elFinderBrowserRTEurl = $modx->getOption(\'elFinderBrowserRTEurl\', $sp);
$elFinderBrowserRTEtitle = $modx->getOption(\'elFinderBrowserRTEtitle\', $sp);
$elFinderBrowserTopNAVurl = $modx->getOption(\'elFinderBrowserTopNAVurl\', $sp);
$elFinderBrowserTopNAVtitle = $modx->getOption(\'elFinderBrowserTopNAVtitle\', $sp);
$elFinderBrowserSHORTtitle = $modx->getOption(\'elFinderBrowserSHORTtitle\', $sp);

$responsivefilemanagerBrowserRTEurl = $modx->getOption(\'responsivefilemanagerBrowserRTEurl\', $sp);
$responsivefilemanagerBrowserRTEtitle = $modx->getOption(\'responsivefilemanagerBrowserRTEtitle\', $sp);
$responsivefilemanagerBrowserTopNAVurl = $modx->getOption(\'responsivefilemanagerBrowserTopNAVurl\', $sp);
$responsivefilemanagerBrowserTopNAVtitle = $modx->getOption(\'responsivefilemanagerBrowserTopNAVtitle\', $sp);
$responsivefilemanagerBrowserSHORTtitle = $modx->getOption(\'responsivefilemanagerBrowserSHORTtitle\', $sp);

$roxyFilemanBrowserRTEtitle = $modx->getOption(\'roxyFilemanBrowserRTEtitle\', $sp);
$roxyFilemanBrowserRTEurl = $modx->getOption(\'roxyFilemanBrowserRTEurl\', $sp);
$roxyFilemanBrowserTopNAVurl = $modx->getOption(\'roxyFilemanBrowserTopNAVurl\', $sp);
$roxyFilemanBrowserTopNAVtitle = $modx->getOption(\'roxyFilemanBrowserTopNAVtitle\', $sp);
$roxyFilemanBrowserSHORTtitle = $modx->getOption(\'roxyFilemanBrowserSHORTtitle\', $sp);

//grab gallery settings
$enableImageGallery = $modx->getOption(\'enableImageGallery\', $sp);
$tinyJSONGalleryTABtitle = $modx->getOption(\'tinyJSONGalleryTABtitle\', $sp) ? : "JSON Image Gallery";
$tinyJSONGalleryTABposition = $modx->getOption(\'tinyJSONGalleryTABposition\', $sp) ? : 0;
$imageGalleryIDs = $modx->getOption(\'imageGalleryIDs\', $sp);
$galleryChunkNameKey = $modx->getOption(\'galleryChunkNameKey\', $sp);
$TinyJSONGalleryTV = $modx->getOption(\'TinyJSONGalleryTV\', $sp) ?:"TinyJSONGalleryTV";
$galleryJSfile = $modx->getOption(\'galleryJSfile\', $sp);

//grab 3rd party TinyMCE inits
$customJS = $modx->getOption(\'customJS\', $sp);
$customJSchunks = $modx->getOption(\'customJSchunks\', $sp);

//this eliminates clutter and confusion: reusuable config is the way of the past and the future - code here will be put in placeholder [[+commonTinyMCECode]]
//apply comma here, not in the chunk calling it --na na, make user leave trailing comma in commonCode Chunk

if ($enableImageGallery == 1) {
  if ($modxEventName == \'OnChunkFormPrerender\' || $modxEventName == \'OnDocFormPrerender\') {
    $galleryIsGolden = 0;
    if ($modxEventName == \'OnChunkFormPrerender\') {
      if($id){
        $thisChunkId = $id;
        $imageGalleryIDsTrue = \'\';
        if($imageGalleryIDs){
          $imageGalleryIDs = preg_replace(\'/\\s+/\', \'\', $imageGalleryIDs);
          $imageGalleryIDs = preg_replace("/[^a-z0-9,-_]+/i", \' \', $imageGalleryIDs);
          $imageGalleryIDs = explode(\',\', $imageGalleryIDs);
          if(in_array($thisChunkId, $imageGalleryIDs)) {
            $imageGalleryIDsTrue = 1;
          }
        }
        $chS = $modx->getObject("modChunk", $thisChunkId);
        $ch = $chS->get(\'name\');
        $chunkGalleryVal = $chS->get(\'content\');
        // if(in_array($thisChunkId, $imageGalleryIDs) || substr($ch, 0, strlen($galleryChunkNameKey)) === $galleryChunkNameKey && $enableImageGallery) {
        if($imageGalleryIDsTrue || strpos($ch, $galleryChunkNameKey) !== false && $enableImageGallery) {
          $modx->regClientStartupHTMLBlock(\'
            <script type="text/javascript">
              var extjsPanelTabs = "modx-chunk-tabs";
              var textareaForJSON = "modx-chunk-snippet";
              var textareaForJSONbk = "modx-chunk-snippet";
              var tinyJSONGalleryGalButtons = "#modx-action-buttons .x-toolbar-left-row";
              var tvChunkGalleryVal = \'.json_encode($chunkGalleryVal).\';
              var backendOrfrontendGallery = "backend";
              var tinyJSONGalleryTABtitle = "\'.$tinyJSONGalleryTABtitle.\'";
              var tinyJSONGalleryTABposition = \'.$tinyJSONGalleryTABposition.\';
              var modxGalleryAssetsUrl = MODx.config.assets_url;
              var galleryBackUpRTEskin = \'.$fileManagerTopNavModalSkin.\';
            </script>
          \');
          $galleryIsGolden = 1;
        }
      }
    }
    if ($modxEventName == \'OnDocFormPrerender\' && $id) {
      if($tvName = $modx->getObject(\'modTemplateVar\', array(\'name\' =>$TinyJSONGalleryTV))){
        $tvId = $tvName->get(\'id\');
        $tvGalleryVal = $resource->getTVValue($tvId);
        // $tvTemplateId = $modx->getObject(\'modTemplateVarTemplate\', array("tmplvarid" => $tvId))->get("templateid");
        if($resourceTemplateId = $resource->get("template")){
          if($tvTemplateId = $modx->getObject(\'modTemplateVarTemplate\', array("tmplvarid" => $tvId)) ){
            $tvTemplateId = $tvTemplateId->get("templateid");
            if ($tvGalleryVal || $tvGalleryVal == \'\'){
              if ($tvTemplateId == $resourceTemplateId) {
                $modx->regClientStartupHTMLBlock(\'
                  <script type="text/javascript">
                    var extjsPanelTabs = "modx-resource-tabs";
                    var textareaForJSON = "tv\'.$tvId.\'";
                    var textareaForJSONbk = "tv\'.$tvId.\'";
                    var tinyJSONGalleryGalButtons = "#modx-action-buttons .x-toolbar-left-row";
                    var tvChunkGalleryVal = \'.json_encode($tvGalleryVal).\';
                    var backendOrfrontendGallery = "backend";
                    var tinyJSONGalleryTABtitle = "\'.$tinyJSONGalleryTABtitle.\'";
                    var tinyJSONGalleryTABposition = \'.$tinyJSONGalleryTABposition.\';
                    var modxGalleryAssetsUrl = MODx.config.assets_url;
                    var galleryBackUpRTEskin = \'.$fileManagerTopNavModalSkin.\';
                  </script>
                \');
                $galleryIsGolden = 1;
              }
            }
          }
        }//////
      }
    }
    if($galleryIsGolden == 1){
      if($galleryJSfile){
        $modx->regClientStartupHTMLBlock("<script src=\'" . $galleryJSfile . "\'\'></script>");
      }
      else{
        $modx->regClientStartupHTMLBlock("<script src=\'" . MODX_ASSETS_URL . "components/tinymcewrapper/gallery/js/TinyJSONGallery.js\'></script>");
      }
    }
  }
}


if ($modxEventName == \'OnManagerPageInit\' || $modxEventName == \'OnDocFormPrerender\') {
  $commonCode = $modx->getChunk(\'TinymceWrapperCommonCode\' . $suffix);
  $commonCode = $commonCode ? $commonCode : \'\';
}

//Quick and dirty hack to allow any and all other 3rd party Extras use TinyMCE


if ($modxEventName == \'OnManagerPageInit\' && $customJS && $customJSchunks) {
  function listArray($thisList){
    $thisList = preg_replace(\'/\\s+/\', \'\', $thisList);
    $thisList = preg_replace("/[^a-z0-9,-_]+/i", \' \', $thisList);
    $thisList = explode(\',\', $thisList);
    return $thisList;
  }
  $getCustomJSchunks = "";
  $customJSchunk = listarray($customJSchunks);
  $i = 0;
  foreach ($customJSchunk as $c) {
    $customJSchunk[$i] = $modx->getChunk(\'TinymceWrapper\'.$c.$suffix)."\\n";
    $getCustomJSchunks.= $customJSchunk[$i];
    $i++;
  }
  $modx->regClientStartupHTMLBlock("<script>" . $getCustomJSchunks . "</script>");
}

//when TinyMCE is temporarily disabled, any new edit is updated before saving
$autoSaveTextAreas = \'
  function autoSaveTextAreas(selectorId){
    setTimeout(function(){
      $("#"+selectorId).on("change", function() {
        tinyMCE.get(selectorId).setContent($("#"+selectorId).val())
        // console.log(selectorId+" has been updated");//debug stuff
      })
    },500)
   }
\';

//let\'s setup some functions and file select callbacks for our supported file browsers
switch ($autoFileBrowser) {
  case \'modxNativeBrowser\':
    $browserRTEurl = $modxNativeBrowserRTEurl;
    $browserRTEtitle = $modxNativeBrowserRTEtitle;
    break;
  case \'elFinderBrowser\':
    $browserRTEurl = \'"\'.$elFinderBrowserRTEurl.\'"\';
    $browserRTEtitle = $elFinderBrowserRTEtitle;
    $browserTopNAVurl = \'\\\'\'.$elFinderBrowserTopNAVurl.\'\\\'\';
    $browserTopNAVtitle = $elFinderBrowserTopNAVtitle;
    $browserShortTitle = $elFinderBrowserSHORTtitle;
    break;
  case \'responsivefilemanagerBrowser\':
    $browserRTEtitle = $responsivefilemanagerBrowserRTEtitle;
    $browserRTEurl = $responsivefilemanagerBrowserRTEurl;
    $browserTopNAVurl = $responsivefilemanagerBrowserTopNAVurl;
    $browserTopNAVtitle = $responsivefilemanagerBrowserTopNAVtitle;
    $browserShortTitle = $responsivefilemanagerBrowserSHORTtitle;
    break;
  case \'roxyFilemanBrowser\':
    $browserRTEtitle = $roxyFilemanBrowserRTEtitle;
    $browserRTEurl = $roxyFilemanBrowserRTEurl;
    $browserTopNAVurl = $roxyFilemanBrowserTopNAVurl;
    $browserTopNAVtitle = $roxyFilemanBrowserTopNAVtitle;
    $browserShortTitle = $roxyFilemanBrowserSHORTtitle;
    break;
}


if ($autoFileBrowser == \'responsivefilemanagerBrowser\') {
  $browserFunctions=\'
    function responsive_filemanager_callback(field_id){
      thisFieldVal = $("#"+field_id).val();
      thisFieldNum = field_id.split("er");
      $("input#tv"+thisFieldNum[1]).val(thisFieldVal);
      $("#tv-image-preview-"+thisFieldNum[1]+" img").attr("title","preview by native MODx Image Browser");
      $("#"+field_id).parents(".modx-tv").find(".twImagePreview").hide().attr("src",thisFieldVal).insertBefore("#tv-image-preview-"+thisFieldNum[1]).fadeIn("slow");
      tinyMCE.activeEditor.windowManager.close();
    }
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      resp = \'.$browserRTEurl.\';
      if (resp.indexOf("?") < 0) {
        resp += "?field_id=" + field_name;
      }
      else {
        resp += "&field_id=" + field_name;
      }
      // console.log(resp); //debug stuff
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: resp,
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2
      }, {
        // oninsert: function(url) {
        //   alert("rte") //debug
        //   win.document.getElementById(field_name).value = url;
        // }
      });
    return false;
      }
  \';
}
elseif ($autoFileBrowser == \'roxyFilemanBrowser\') {
  $browserFunctions=\'
    autoFileBrowser = \'.$autoFileBrowser.\';
      function \'.$autoFileBrowser.\'(field_name, url, type, win) {
        roxyFileman = \'.$browserRTEurl.\';
        if (roxyFileman.indexOf("?") < 0) {
          roxyFileman += "?type=" + type;
        }
        else {
          roxyFileman += "&type=" + type;
        }
        roxyFileman += "&input=" + field_name + "&value=" + win.document.getElementById(field_name).value;
        if(tinyMCE.activeEditor.settings.language){
          roxyFileman += "&langCode=" + tinyMCE.activeEditor.settings.language;
        }
        tinyMCE.activeEditor.windowManager.open({
          title: "\'.$browserRTEtitle.\'",
          url: roxyFileman,
          plugins: "media",
          width : window.innerWidth / 1.2,
          height : window.innerHeight / 1.2
        }, {
          oninsert: function(url) {
            win.document.getElementById(field_name).value = url;
          }
        });
      return false;
      }
  \';
}
//thanks to Denis 
elseif ($autoFileBrowser == \'modxNativeBrowser\' && $modxNativeBrowserQuirkMode) {
  $modx->regClientStartupHTMLBlock("<style>.modx-browser {z-index: 99999!important;}</style>");
  $browserFunctions=\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      var path = url.substring(0,url.lastIndexOf("/")+1);
      var w = MODx.load({
        xtype: "modx-browser",
        multiple: true,
        //If there is no path, use default
        openTo: path || \'.$modxNativeBrowserRTEurl.\',
        listeners: {
          "select": {fn:function(data) {
            win.document.getElementById(field_name).value = data.relativeUrl;
            MODx.fireEvent("select",data);
          },scope:this}
        }
      });
      w.show();
    }
  \';
}
elseif ($autoFileBrowser == \'modxNativeBrowser\') {
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: \'.$browserRTEurl.\',
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2,
        classes: "twAutoBrowser",
        onPostRender: function(){
          $(".mce-twAutoBrowser iframe").attr("id","twAutoBrowser").load(function(){
            var checkRTEbuttons = setInterval(function() {
              if ($("#twAutoBrowser").contents().find(".modx-browser-rte-buttons").length) {
                $("#twAutoBrowser").contents().find(".modx-browser-rte-buttons").hide();
                clearInterval(checkRTEbuttons);
              }
            }, 50);
          })
        },
      }, {
        oninsert: function(url) {
          win.document.getElementById(field_name).value = url;
        }
      });
    return false;
    }
  \';
}
else{
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: \'.$browserRTEurl.\',
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2
      }, {
        oninsert: function(url) {
          win.document.getElementById(field_name).value = url;
        }
      });
    return false;
    }
  \';
}

//what happens when you click the enable/disable checkbox
//also for MIGX TVs

$enableDisableTinyClick = \'
  function tinyTVcheck(editor) {
    
    if(tinymce.get(editor) && !tinyMCE.get(editor).getParam("twExoticMarkdownEditor",false)){
      autoSaveTextAreas(editor);
      if($("input[data-tiny="+editor+"]").is(":checked")){
        tinymce.get(editor).hide();
        $("input[data-tiny="+editor+"]").attr("title","Show TinyMCE");
      }
      else{
        tinymce.get(editor).show();
        $("input[data-tiny="+editor+"]").trigger("change").attr("title","Temporarily Hide TinyMCE");
      }
    }
    else{
      $("input[data-tiny="+editor+"]").remove();
      if(typeof tinymce !== "undefined"){
        tinymce.activeEditor.windowManager.alert("Not applicable here");
      }
      else{
        alert("Not applicable here");
      }
    }
  }
  // $(".tinyTVcheck").on("mouseup",function() {
  //   autoSaveTextAreas($(this).attr("data-tiny"));
  //   if (this.checked) {
  //     tinymce.get($(this).attr("data-tiny")).hide();
  //     $(this).trigger("change").attr("title","Enable TinyMCE");
  //   }
  //   else{
  //     tinymce.get($(this).attr("data-tiny")).show();
  //     $(this).trigger("change").attr("title","Disable TinyMCE");
  //   }
  //   });
\';

//lock the below to this event, to prevent spill over
if ($modxEventName == \'OnDocFormPrerender\') {
  $enableDisableTiny = \'\';
  //is the enable/disable TinyMCE option selected? If so, let\'s create all the buttons at once; this will be split later on. This is good for TVs that have default content, and user wishes to revert. Disable TinyMCE, then revert.
  //there are two $("#ta") below; don\'t ask me why the Articles\' Container/Child are has own thing going own here
  if ($disable == 1) {
  //prepend is better than append - you\'ll see!!!
    $enableDisableTiny = \'
    $("#modx-resource-introtext").parent().parent().prepend("<input data-tiny=\\\'modx-resource-introtext\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"modx-resource-introtext\\")\\\' />&nbsp;&nbsp;&nbsp;");@
    $("#modx-resource-description").parent().parent().prepend("<input data-tiny=\\\'modx-resource-description\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"modx-resource-description\\")\\\' />&nbsp;&nbsp;&nbsp;");@
    $("#ta").parents("#modx-resource-content").find(".x-panel-header-text").prepend("<input data-tiny=\\\'ta\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"ta\\")\\\' />&nbsp;&nbsp;&nbsp;");
    if($("#articles-box-publishing-information").length){
      $("#ta").parents(".contentblocks_replacement").find("label[for=ta]").prepend("<input data-tiny=\\\'ta\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"ta\\")\\\' />&nbsp;&nbsp;&nbsp;");
    }
    if($("#modx-resource-tabs__articles-tab-template").length){
      $("#modx-resource-header").append("<p id=\\\'tinyArtAlert\\\' style=\\\'width:70%;margin:0 auto;background-color:#32AB9A;padding:10px;border-radius:10px;color:white;text-align:center;\\\'><b>TinymceWrapper Raw Code Protection:</b><br>Check this Articles Container > Template [Tab] > Content, before saving.<br>Unchecking the box will not only disable but remove TinyMCE, thus protecting your code</p>");
      $("#ta").parent().parent().find("label[for=ta]").prepend("<input data-tiny=\\\'ta\\\' checked=\\\'checked\\\' title=\\\'Remove TinyMCE \\\' type=\\\'checkbox\\\' class=\\\'tinyTVchecky\\\' onmouseup=\\\'javascript:tinymce.get(\\"ta\\").remove();$(this).remove();$(\\"#tinyArtAlert\\").fadeOut().remove();\\\' />&nbsp;&nbsp;&nbsp;");
    }
  \';
  //let\'s split the enable/disable checkboxes so that they don\'t appear randomly or unexpectedly
  $enableDisableTiny = explode("@", $enableDisableTiny);
  }

  //All TVs are here nicely placed independent of strict conditions, just in case we want to activate TVS even when RTE is disabled for a particular resource
  if ($tvs == 1) {
    $tvsChunk = $modx->getChunk(\'TinymceWrapperTVs\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
    if ($tvsChunk) {
      //let\'s remove the checkboxes that enables/disables TinyMCE for the TVs
      //let\'s allow the TV reset button to work through TinyMCE, either enabled or disabled - textareas are updated .on change + the delay is neccesary since we are pseudo binding to existing click event
      if ($disable == 1) {
        $richTv = \'
          if($(".modx-richtext").length){
            $(".modx-richtext").css({"overflow": "auto", "width": "100%", "min-height": "100px", "resize": "vertical"});
            function updateReset(updateR){
              if(tinymce.get(updateR)){
                setTimeout(function(){
                  tinyMCE.get(updateR).setContent($("#"+updateR).val());
                  // console.log(updateR+" has been updated");//debug stuff
                },200)
              }
            }
            $.each($(".modx-richtext"), function() {
              var updateR = $(this).attr("id");
              $(this).parents(".modx-tv").find(".modx-tv-reset").attr("data-tiny",this.id).on("click", function(){
                updateReset($(this).attr("data-tiny"));
              });
              $(this).parent().parent().prepend("<input data-tiny=\\\'" + this.id + "\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' onmouseup=\\\'tinyTVcheck(\\""+this.id+"\\")\\\' />");
            });
            setTimeout(function(){
              \' . $tvsChunk . \'
            },1000);
          }
        \';
      } 
      else {
        $richTv = \'
          if($(".modx-richtext").length){
            function updateReset(updateR){
              setTimeout(function(){
                tinyMCE.get(updateR).setContent($("#"+updateR).val());
                // console.log(updateR+" has been updated");//debug stuff
              },200)
            }
            $.each($(".modx-richtext"), function() {
              var updateR = $(this).attr("id");
              $(this).parents(".modx-tv").find(".modx-tv-reset").attr("data-tiny",this.id).on("click", function(){
                updateReset($(this).attr("data-tiny"));
              });
            });
            setTimeout(function(){
              \' . $tvsChunk . \'
            },1000);
          }
        \';
      }
    }
  }
  if ($fileImageTVs == 1) {
    /*
    - append hidden input#tinyFileImageBrowser to the body so that we have at least one active editor, in case the user has disabled TinyMCE for all other textareas and TVs
    - do some magic: create the respective image and file twBrowser buttons with appropriate properties when the page is really ready
    - create rudimentary image prev something similar to the native MODx\' file browser
    - init hidden #tinyFileImageBrowser
    - Create tinymce #tinyFileImageBrowser on condition; give a definite CSS theme (only when one is not already loaded) to avoid overriding issues. Allow cross-browser amiability by setting to inline:true
    - add twBrowser menu button to MODx Media drop down - depends on the option fileImageTvs
    - NOTE - Roxy don\'t have a callback...no preview 
    */
    $browserTVs = \'
      function imageFileTVpop(field_name, url, type, win) {
        thisUrl = \'.$browserRTEurl.\';
        if (thisUrl.indexOf("dialog") > 0) {
            thisUrl = thisUrl.replace("popup=1", "popup=0");
          if (thisUrl.indexOf("?") < 0) {
            thisUrl += "?field_id="+field_name;
          }
          else {
            thisUrl += "&field_id="+field_name;
          }
        }
        if (thisUrl.indexOf("fileman") > 0) {
          if (thisUrl.indexOf("?") < 0) {
            thisUrl += "?type=" + type;
          }
          else {
            thisUrl += "&type=" + type;
          }
          thisUrl += "&input=" + field_name + "&value=" + document.getElementById(field_name).value;
        }

        tinyMCE.activeEditor.windowManager.open({
          title: "\'.$browserRTEtitle.\'",
          url: thisUrl,
          width : window.innerWidth / 1.2,
          height : window.innerHeight / 1.2
        }, {
          oninsert: function(url) {
            $("#"+field_name).val(url);
            thisFieldNum = field_name.split("er");
            $("input#tv"+thisFieldNum[1]).val(url);
            $("#tv-image-preview-"+thisFieldNum[1]+" img").hide().attr({"src":url, "title":"preview by \'.$browserShortTitle.\'"}).fadeIn();
            // $("#"+field_name).parents(".modx-tv").find(".twImagePreview").hide().attr("src",url).insertBefore("#tv-image-preview-"+thisFieldNum[1]).fadeIn("slow");
            tinyMCE.activeEditor.windowManager.close();
          }
        });
      return false;
      }
      Ext.onReady(function(){
        replaceDefaultFileImageTVbutton = \'.$replaceDefaultFileImageTVbutton.\';
        setTimeout(function(){
          if(!$("#tinyFileImageBrowser").length){
            $("body").append("<input id=\\\'tinyFileImageBrowser\\\' type=\\\'hidden\\\' value=\\\'\\\' />");
          }
           $("input[id^=tvbrowser]").each(function(){
              fileOrImage = $(this).parents(".modx-tv").find(".x-form-file-trigger").attr("id");
              if($("#"+fileOrImage).length){
                twImageFileOnClick = "imageFileTVpop($(this).attr(\\\'data-tiny\\\'))";
                twImageFileBtn = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;(all&nbsp;files)&nbsp;\\\';
                twImageFileBtnTitle = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;All-File&nbsp;Browser&nbsp;\\\';
                twImageClass = \\\'twImageFileBtnClass x-form-trigger x-form-file-trigger\\\';
                twImagePreview = "";
              }
              else{
                twImageFileOnClick = "imageFileTVpop($(this).attr(\\\'data-tiny\\\'))";
                twImageFileBtn = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;(images)&nbsp;\\\';
                twImageFileBtnTitle = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;Image-Only&nbsp;Browser&nbsp;\\\';
                twImagePreview = "<img class=\\\'twImagePreview\\\' title=\\\'preview by \'.$browserShortTitle.\' Image Browser\\\' src=\\\'\\\' style=\\\'width:100px;display:none;\\\' />";
                twImageClass = \\\'twImageFileBtnClass x-form-trigger x-form-image-trigger\\\';
              }
              if(replaceDefaultFileImageTVbutton == 1){
                $(this).parents(".modx-tv").find(".x-form-trigger").replaceWith("<div class=\\\'"+twImageClass+"\\\' data-tiny="+this.id+"  title="+twImageFileBtnTitle+" onclick="+twImageFileOnClick+"></div>"+twImagePreview);
              }
              else{
                $(this).parents(".x-form-item")
                .find(".modx-tv-caption")
                .parent()
                .prepend("<input class=\\\'twImageFileBtnClass x-form-field-wrap x-form-trigger\\\' data-tiny="+this.id+" type=\\\'button\\\' value="+twImageFileBtn+" title="+twImageFileBtnTitle+" onclick="+twImageFileOnClick+">"+twImagePreview);
              }
              if(tinymce.editors.length < 1){
                tinymce.init({
                  selector: "#tinyFileImageBrowser",
                  skin_url: \'.$fileManagerTopNavModalSkin.\',
                  inline:true,
                  forced_root_block : "",
                  force_br_newlines : false,
                  force_p_newlines : false
                })
              }
           })
        },1000);
      })
    \';
    if($autoFileBrowser ==\'modxNativeBrowser\'){
      $browserTVs = \'\';
    }
  }
}

//if user selects the option to activate Ace / CodeMirror, we save him/her the trip of heading to System Settings - is this being too officious or intrusive?
if ($activateAceOrCodeMirror) {
  $fireEditor = 0;
  if ($whichElementEditor !== \'TinymceWrapper\') {
    $whichEl = $modx->getObject(\'modSystemSetting\', \'which_element_editor\');
    $whichEl->set(\'value\', \'TinymceWrapper\');
    $whichEl->save();
  }
  $onlyElementsFiles = \'OnTempFormPrerender,OnSnipFormPrerender,OnChunkFormPrerender,OnPluginFormPrerender,OnFileEditFormPrerender\';
  $onlyElementsFiles = explode(\',\', $onlyElementsFiles);
  $twGetResourceContentType = "";
  // experimental - OnManagerPageInit or other ... would have been good but...
  if($useAceOrCodeMirrorEveryWhere){
    $updateResource = "resource/update";
    $newResource = "resource/create";
    $updateCreateElement = "element/";
    $updateCreateFile = "system/file/";
    $fireEditor = 1;

    // existing or new elements or files
    if (isset($_GET["a"]) && !$useAceOrCodeMirrorOnElementsFiles && (strpos($_GET["a"], $updateCreateElement) !== false || strpos($_GET["a"], $updateCreateFile) !== false)) {
        $fireEditor = 0;
    }

    // existing resource with RT value
    if (isset($_GET["a"], $_GET["id"]) && strpos($_GET["a"], $updateResource) !== false) {
      $twGetResourceContentType = $modx->getObject("modResource",$_GET["id"])->getOne(\'ContentType\')->get(\'mime_type\');
      if (!$activateAceOrCodeMirrorOnRichText && $modx->getObject("modResource",$_GET["id"])->get(\'richtext\')) {
        $fireEditor = 0;
      }
      if (!$useAceOrCodeMirrorOnResources) {
        $fireEditor = 0;
      }
    }
    // new resource with no RT value
    // if (isset($_GET["a"],$_GET["id"]) && $_GET["id"] == 0 && strpos($_GET["a"], $newResource) !== false) {
    if (isset($_GET["a"]) && strpos($_GET["a"], $newResource) !== false) { //expand criteria for Collection Extra and oter weird stuff
      if (!$activateAceOrCodeMirrorOnNewResource) {
        $fireEditor = 0;
      }
      // new resource with no RT value but System settings default RT value
      if ($activateAceOrCodeMirrorOnNewResource && $richtext_default == 1) {
        $fireEditor = 0;
      }
      if (!$useAceOrCodeMirrorOnResources) {
        $fireEditor = 0;
      }
    }
  }

  if($useAceOrCodeMirrorOnElementsFiles && in_array($modxEventName, $onlyElementsFiles)){
    $fireEditor = 1;
  }

  if($useAceOrCodeMirrorOnResources && $modxEventName == "OnDocFormPrerender"){
    $fireEditor = 1;
    // existing resource has contenttype
    if($id){
      $twGetResourceContentType = $resource->getOne(\'ContentType\')->get(\'mime_type\');
    }
    // existing resource with RT value
    if (!$activateAceOrCodeMirrorOnRichText && $id && $resource->get(\'richtext\')) {
      $fireEditor = 0;
    }
    // new resource with no RT value
    if (!$activateAceOrCodeMirrorOnNewResource && !$id) {
      $fireEditor = 0;
    }
    // new resource with no RT value but System settings default RT value
    if ($activateAceOrCodeMirrorOnNewResource && !$id && $richtext_default == 1) {
      $fireEditor = 0;
    }
    if ($useAceOrCodeMirrorEveryWhere) {
      $fireEditor = 0;
    }
  }

  //make sure that this never fires twice, once at OnManagerPageInit and other events.
  if($fireEditor == 1){
    if ($activateTinyMCE !== 1 && $tvSuperAddict !== 1 && $jQuerySrc) {
      $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
    }
    if ($activateAceOrCodeMirror == "Ace") {
      $editorOutput= $modx->getChunk(\'TinymceWrapperAce\'.$chunkSuffix, array(\'AceSrc\' => $AceSrc, \'AceTHEME\' => $AceTHEME));
      $modx->regClientStartupScript($AceSrc.\'ace.js\');
    }
    else{
      $editorOutput= $modx->getChunk(\'TinymceWrapperCodeMirror\'.$chunkSuffix, array(\'CodeMirrorSrc\' => $CodeMirrorSrc, \'CodeMirrorTHEME\' => $CodeMirrorTHEME));
      $modx->regClientStartupScript($CodeMirrorSrc.\'codemirror.min.js\');
    }
    $exportVariables = \'
      twGetResourceContentType = "\'.$twGetResourceContentType.\'";
      fileManagerTopNavModalSkin = \'.$fileManagerTopNavModalSkin.\';
    \';
    $modx->regClientStartupHTMLBlock("<script>" . $exportVariables . $editorOutput . "</script>");
  }
}

//if user selects the option to activate this wrapper, we save him/her the trip of heading to System Settings - is this being too officious or intrusive?
if ($activateTinyMCE == 1) {
  if ($useEditor !== 1 || $whichEditor !== \'TinymceWrapper\') {
    $use = $modx->getObject(\'modSystemSetting\', \'use_editor\');
    $use->set(\'value\', 1);
    $use->save();
    $which = $modx->getObject(\'modSystemSetting\', \'which_editor\');
    $which->set(\'value\', \'TinymceWrapper\');
    $which->save();
  }
  //leave all elements alone - attack only resources
  if ($modxEventName == \'OnDocFormPrerender\') {
    //check if user wants to load TinyMCE for New Resources
      $loadTiny = 0; //default
    if($id && $resource->get(\'richtext\')) { //existing resource with RTE clicked
      $loadTiny = 1;
      }
    if($loadTiny == 0 && $newResources == 1 && $richtext_default == 1 && !$id) {
      $loadTiny = 1;
    }

    if ($loadTiny == 1) {
      //should we load jQuery?
      if ($jQuerySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
      }
      //should we load TinyMCE (from CDN or assets folder)?
      if ($tinySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
      }
      //let\'s init introtext, description and content textareas only if user has specified so in this plugin\'s properties
      if ($introtext == 1) {
        $introChunk = $modx->getChunk(\'TinymceWrapperIntrotext\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
        if ($introChunk) {
          $intro = $enableDisableTiny[0] . $introChunk;
        }
      }
      if ($description == 1) {
        $descChunk = $modx->getChunk(\'TinymceWrapperDescription\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
        if ($descChunk) {
          $desc = $enableDisableTiny[1] . $descChunk;
        }
      }
      if ($content == 1) {
        $conChunk = $modx->getChunk(\'TinymceWrapperContent\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
        if ($conChunk) {
          $con = $enableDisableTiny[2] . $conChunk;
        }
      }
      //all textareas depend on whether the Resource is Rich Text-enabled. We use so many IFs to protect against error
      //any and all Rich TVs + File and Image TVs will now be initiated
      //Now let\'s do the real init of all textareas
      //seems Ext.onReady is better than setTimeout, delay of 400
      $modx->regClientStartupHTMLBlock("<script>" . $enableDisableTinyClick . $browserFunctions . $autoSaveTextAreas . $browserTVs . "Ext.onReady(function () {" . $intro . $desc . $con . $richTv . "},this,{delay:".$addTinyMCEloadDelay."});</script>");
    }
    //let\'s see if the person wants TVs activated even when RTE is disabled for the Resource.
    elseif ($id && !$resource->get(\'richtext\')) {
        if ($tvAddict == 1) {
          if ($jQuerySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
          }
          if ($tinySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
          }
          $modx->regClientStartupHTMLBlock("<script>" . $enableDisableTinyClick . $browserFunctions . $autoSaveTextAreas . $browserTVs . "Ext.onReady(function () {" . $richTv . "},this,{delay:".$addTinyMCEloadDelay."});</script>");
        }
    }
  }
}
else{
  if ($modxEventName == \'OnDocFormPrerender\') {
        if ($tvSuperAddict == 1) {
          if ($jQuerySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
          }
          if ($tinySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
          }
          $modx->regClientStartupHTMLBlock("<script>" . $enableDisableTinyClick . $browserFunctions . $autoSaveTextAreas . $browserTVs . "Ext.onReady(function () {" . $richTv . "},this,{delay:".$addTinyMCEloadDelay."});</script>");
        }
  }
}

if ($modxEventName == \'OnManagerPageInit\' || $modxEventName == \'OnDocFormPrerender\') {
  $mediaPopUp =\'\';
  if ($fileManagerTopNavLink == 1 && $autoFileBrowser !== \'modxNativeBrowser\') {
    // inject file browser link to Manager Top Nav Media dropdown
    // if modxNativeBrowserTopNAVpresent is set to NO, remove native link in topNAV only when native browser is not in use
    $mediaPopUp = \'
      var fileManagerTopNavModal = "\'.$fileManagerTopNavModal.\'";
      fileManagerTopNavModalSkin = \'.$fileManagerTopNavModalSkin.\';
      Ext.onReady(function(){
        if (typeof tinyMCE !== "undefined" && fileManagerTopNavModal == "1") {
          $("body").append("<div id=\\\'tinyTopNAV\\\' style=\\\'display:none!important;border:0!important;outline:0!important;width:0;height:0;\\\'></div>");
          if(tinymce.editors.length < 1){
            tinymce.init({
              selector: "#tinyTopNAV",
              skin_url: \'.$fileManagerTopNavModalSkin.\',
              inline:true,
              forced_root_block : "",
              force_br_newlines : false,
              force_p_newlines : false
            })
          }
        }
      },this,{delay: 50})
      function mediaPopup(url){
        if ($("#tinyTopNAV").length) {
          tinyMCE.activeEditor.windowManager.open({
            title: "\'.$browserRTEtitle.\'",
            url: url,
            width : window.innerWidth / 1.2,
            height : window.innerHeight / 1.2
          },
          {
            oninsert: function(e) {
              e.preventDefault()
              return false;
          }
          })
        }
        else{
          var w = 880;
          var h = 570;
          var l = Math.floor((screen.width-w)/2);
          var t = Math.floor((screen.height-h)/2);
          var win = window.open(url, "", "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
        }
      }

      taskCounter = 0;
      var linkCheck = setInterval(function(){
        //requires no jQuery or TinyMCE - will work even if activateTinyMCE is false
        var fileBrowserBro = document.getElementById("file_browser");
        if(fileBrowserBro){
          var browserName = "\'.$autoFileBrowser.\'";
          var modxNativeBrowserTopNAVpresent = "\'.$modxNativeBrowserTopNAVpresent.\'";
          if(modxNativeBrowserTopNAVpresent !== "1" && browserName !== "modxNativeBrowser"){
            fileBrowserBro.style.display = "none";
          }
          taskCounter++;
          fileBrowserBro.insertAdjacentHTML( "beforebegin", "<li id=\\"tinymcewrapperTopNav\\"><a href=\\"javascript:;\\" onclick=\\"mediaPopup(\'.$browserTopNAVurl.\')\\">\'.$browserTopNAVtitle.\'<span class=\\"description\\">\'.$browserTopNAVsubtext.\'</span></a></li>");
        }
        if(taskCounter = 1)
          {clearInterval(linkCheck);
          }
      },1000);
    \';
     $modx->regClientStartupHTMLBlock("<script>" . $mediaPopUp . "</script>");
  }

  //let\'s catch only the textarea[content] when it is created. You can use livejquery or arrive.js if you like
  //make it non-obstrusive - mouseenter seems better than mouseout - works when modal pops and cursor is already on the textarea

  $quickUpdateCreate = $modx->getOption(\'quickUpdateCreate\', $sp);
  $quick = \'\';
  $quickChunk = $modx->getChunk(\'TinymceWrapperQuickUpdate\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));

  if ($quickChunk) {
    $quick = $quickChunk;
  }
  if ($quickUpdateCreate == 1){
    //do not load these twice when resources are being edited
    if ($modxEventName == \'OnManagerPageInit\') {
      if ($jQuerySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
      }
      if ($tinySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
      }
    }

    $quickUpdateTinyMCE = \'
      removeCodeMirror = 0;
      $(document).on("mouseenter", ".modx-window", function () {
        var tinyContent = $(this).find("textarea[name=content]");
        quickyId = "#"+tinyContent.attr("id");
        dataTiny = tinyContent.attr("id");
        // if ($(this).has("textarea[name=content]").length){//will catch Quick edit files from server
        if ($(this).has("input[name=published]").length && $(this).has("textarea[name=content]").length){
          if ($(this).has(".tinyEn").length){
          }
          else{
          // tinyContent.parent().parent().find("label").prepend("<button class=\\\'tinyEn x-form-field-wrap x-form-trigger\\\' onclick=enableTiny(quickyId,dataTiny)>Edit with TinyMCE?</button>&nbsp;&nbsp;&nbsp;");
          $(this).find(".x-toolbar-left-row").prepend("<p onclick=enableTiny(quickyId,dataTiny) class=\\\'x-btn x-btn-small x-btn-icon-small-left x-btn-noicon\\\' unselectable=\\\'on\\\'><em><button class=\\\'tinyEn x-btn-text\\\'>Edit with TinyMCE</button></em></p>");
          $(this).find(".tinyEn").attr("data-tiny",dataTiny);
          // $(this).find("button:contains(\\\'Close\\\')").first().attr("data-tiny","close-"+dataTiny);
          // $(this).find("button:contains(\\\'Save\\\')").first().attr("data-tiny","save-"+dataTiny);
          }
        }
      // })
      // .on("mouseout", tinymce.activeEditor, function () {
        // if(tinymce.editors.length > 1){}
        // if (tinyMCE.activeEditor !== null){
        //   if(tinyMCE.activeEditor.isHidden() != true){
        //     tinyMCE.activeEditor.save();
        //     javascript:console.log("saved");
        //   }
        // }
      });
      function enableTiny(quickyId,id){
        if($(quickyId).is(":visible")){
          enableTinyInit(quickyId);
          var id = dataTiny;
          $("button[data-tiny=\\\'"+id+"\\\']").html("Disable TinyMCE").parent().parent().attr("onclick","disTiny(dataTiny)");
        }
      }
      function disTiny(dataTiny){
        var dataTiny = dataTiny;
        tinymce.get(dataTiny).hide();
        $("button[data-tiny=\\\'"+dataTiny+"\\\']").html("Enable TinyMCE").parent().parent().attr("onclick","enTiny(dataTiny)");
        removeCodeMirror = 0;
        $(quickyId).parents(".modx-window").find(".CodeMirror, div.coder").remove();
        $(quickyId).parents(".modx-window").find(".ace_editor, div.coder").remove();
      }
      function enTiny(dataTiny){
        if($(quickyId).is(":visible")){
          $(quickyId).fadeIn().parents(".modx-window").find(".CodeMirror, div.coder").remove();
          $(quickyId).fadeIn().parents(".modx-window").find(".ace_editor, div.coder").remove();
          removeCodeMirror = 1;
          var dataTiny = dataTiny;
          tinymce.get(dataTiny).show();
          $("button[data-tiny=\\\'"+dataTiny+"\\\']").html("Disable TinyMCE").parent().parent().attr("onclick","disTiny(dataTiny)");
        }
      }
      function enableTinyInit(quickyId){
        $(quickyId).fadeIn().parents(".modx-window").find(".CodeMirror, div.coder").remove();
        $(quickyId).fadeIn().parents(".modx-window").find(".ace_editor, div.coder").remove();
        removeCodeMirror = 1;
        $(quickyId).parents(".modx-window").find(".x-tab-panel-body.x-tab-panel-body-top").css({"overflow":"hidden","overflow-y":"auto"});
        \' .$quick. \'
      }
      \';
    $modx->regClientStartupHTMLBlock("<script>" . $browserFunctions . $quickUpdateTinyMCE . "</script>");
  }
}',
      'locked' => 0,
      'properties' => 'a:59:{s:15:"activateTinyMCE";a:7:{s:4:"name";s:15:"activateTinyMCE";s:4:"desc";s:260:"To use TinyMCE, this has to be set to Yes; this plugin will then disable whatever RTE you might have used before now.If set to false, with tvSuperAddict you can use the custom file browsers for your File/Image TVs, and also use TinyMCE(CDN) for RichTextareaTVs";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:19:"addTinyMCEloadDelay";a:7:{s:4:"name";s:19:"addTinyMCEloadDelay";s:4:"desc";s:174:"Default: 0. When using with an Extra that produces textareas on the fly, you might need a delay. 2100 works with Lingua. The longer the delay, the badder the user experience.";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:11:"chunkSuffix";a:7:{s:4:"name";s:11:"chunkSuffix";s:4:"desc";s:280:"This plugin will create six chunks for you; it will not override them once created, but you were better off duplicating them.
PLEASE simply add a suffix (_test or -su) to your new name.
TinymceWrapperIntrotext becomes TinymceWrapperIntrotext-test or TinymceWrapperIntrotext-suffix";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:21:"disableEnableCheckbox";a:7:{s:4:"name";s:21:"disableEnableCheckbox";s:4:"desc";s:111:"Do you want a checkbox to appear before every TinyMCE textarea, to quickly disable/enable a particular TinyMCE?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:21:"fileManagerTopNavLink";a:7:{s:4:"name";s:21:"fileManagerTopNavLink";s:4:"desc";s:245:"Add custom File Manager link to Manager Top Nav > Media drop-down menu (Vanilla JS, no jQuery or TinyMCE loaded).
This will work whether you are using RTE or not, that is, even if activateTinyMCE is set to false; wherever you are in the Manager.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:22:"fileManagerTopNavModal";a:7:{s:4:"name";s:22:"fileManagerTopNavModal";s:4:"desc";s:55:"If you want the custom file browser to pop into a modal";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:26:"fileManagerTopNavModalSkin";a:7:{s:4:"name";s:26:"fileManagerTopNavModalSkin";s:4:"desc";s:77:"Bear in mind, this skin can affect your RTEs, so make the skin calls the same";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:76:"MODx.config.assets_url+"components/tinymcewrapper/tinymceskins/modxPericles"";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:6:"jQuery";a:7:{s:4:"name";s:6:"jQuery";s:4:"desc";s:118:"This plugin requires jQuery in the order that it is loaded. Leave blank if you already have it running in the Manager.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:43:"https://code.jquery.com/jquery-2.1.3.min.js";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:12:"newResources";a:7:{s:4:"name";s:12:"newResources";s:4:"desc";s:173:"If you set richtext_default in System Settings, new resources will have the RTE clicked automatically.
Do you want TinyMCE to load also, automatically, for the new resource?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:7:"tinySrc";a:7:{s:4:"name";s:7:"tinySrc";s:4:"desc";s:68:"You may use either TinyMCE CDN or TinyMCE located on your own folder";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:34:"//cdn.tinymce.com/4/tinymce.min.js";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:8:"tvAddict";a:7:{s:4:"name";s:8:"tvAddict";s:4:"desc";s:187:"Do you want your TVs (Rich/File/Image) to be wrapperjacked by this plugin even if you have RTE disabled for the particular resource? This will work even in the Articles Extra (hopefully!)";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:13:"tvSuperAddict";a:7:{s:4:"name";s:13:"tvSuperAddict";s:4:"desc";s:202:"Even though you have another RTE in use (that is, you have set activateTinyMCE to false), you can still use the custom filebrowsers for your File/Image TVs, and also use TinyMCE(CDN) for RichTextareaTVs";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:17:"TinyJSONGalleryTV";a:7:{s:4:"name";s:17:"TinyJSONGalleryTV";s:4:"desc";s:98:"TV to use to transform any Resource into a Gallery. Default is TinyJSONGalleryTV -- Type: Textarea";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:17:"TinyJSONGalleryTV";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:18:"enableImageGallery";a:7:{s:4:"name";s:18:"enableImageGallery";s:4:"desc";s:129:"Presently incomapatible with Image+ and Gallery Extra. Hopefully, either party will resolve the issue before the millennium ends.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:19:"galleryChunkNameKey";a:7:{s:4:"name";s:19:"galleryChunkNameKey";s:4:"desc";s:68:"Any Chunk name containing this keyword will be turned into a Gallery";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:9:"myGallery";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:13:"galleryJSfile";a:7:{s:4:"name";s:13:"galleryJSfile";s:4:"desc";s:156:"absolute url to custom file that controls the gallery; if empty, default file will be used = /assets/components/tinymcewrapper/gallery/js/TinyJSONGallery.js";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:15:"imageGalleryIDs";a:7:{s:4:"name";s:15:"imageGalleryIDs";s:4:"desc";s:91:"Comma-separated list of chunk id. Any Chunk whose id is here will be turned into a Gallery.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"0,0,0";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:26:"tinyJSONGalleryTABposition";a:7:{s:4:"name";s:26:"tinyJSONGalleryTABposition";s:4:"desc";s:65:"By default, the Gallery tsb comes first. 0 = first.... 1 , 2 , 10";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:23:"tinyJSONGalleryTABtitle";a:7:{s:4:"name";s:23:"tinyJSONGalleryTABtitle";s:4:"desc";s:47:"The title on the tab in your resource or chunk.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:18:"JSON Image Gallery";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:8:"customJS";a:7:{s:4:"name";s:8:"customJS";s:4:"desc";s:200:"For running custom JavaScript in your Manager. Use scenario: any and all other 3rd party MODx Extras within which you wish to use TinyMCE. Or just about any other textarea you find in the MODX Manager";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:33:"00 Textareas to transform - et al";}s:14:"customJSchunks";a:7:{s:4:"name";s:14:"customJSchunks";s:4:"desc";s:327:"Comma-separated list of any 3rd party MODX Extras you wish to be infused with TinyMCE. E.G: Gallery,ContentBlocks,ETC,ETC... Then create the corresponding chunk - TinymceWrapperContentBlocks. These chunks also are affected by the chunkSuffix setting. You can use with just about any other textarea you find in the MODX Manager.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:33:"00 Textareas to transform - et al";}s:6:"AceSrc";a:7:{s:4:"name";s:6:"AceSrc";s:4:"desc";s:74:"Toss in latest Ace CDN or local url...never be outdated again! Hurray..!!!";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:49:"https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.4/";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:8:"AceTHEME";a:7:{s:4:"name";s:8:"AceTHEME";s:4:"desc";s:60:"35 themes to work with (BRIGHT and DARK) knock yourself out!";s:4:"type";s:4:"list";s:7:"options";a:36:{i:0;a:2:{s:4:"text";s:7:"(empty)";s:5:"value";s:0:"";}i:1;a:2:{s:4:"text";s:8:"ambiance";s:5:"value";s:8:"ambiance";}i:2;a:2:{s:4:"text";s:5:"chaos";s:5:"value";s:5:"chaos";}i:3;a:2:{s:4:"text";s:6:"chrome";s:5:"value";s:6:"chrome";}i:4;a:2:{s:4:"text";s:6:"clouds";s:5:"value";s:6:"clouds";}i:5;a:2:{s:4:"text";s:15:"clouds_midnight";s:5:"value";s:15:"clouds_midnight";}i:6;a:2:{s:4:"text";s:6:"cobalt";s:5:"value";s:6:"cobalt";}i:7;a:2:{s:4:"text";s:14:"crimson_editor";s:5:"value";s:14:"crimson_editor";}i:8;a:2:{s:4:"text";s:4:"dawn";s:5:"value";s:4:"dawn";}i:9;a:2:{s:4:"text";s:11:"dreamweaver";s:5:"value";s:11:"dreamweaver";}i:10;a:2:{s:4:"text";s:7:"eclipse";s:5:"value";s:7:"eclipse";}i:11;a:2:{s:4:"text";s:6:"github";s:5:"value";s:6:"github";}i:12;a:2:{s:4:"text";s:7:"gruvbox";s:5:"value";s:7:"gruvbox";}i:13;a:2:{s:4:"text";s:12:"idle_fingers";s:5:"value";s:12:"idle_fingers";}i:14;a:2:{s:4:"text";s:8:"iplastic";s:5:"value";s:8:"iplastic";}i:15;a:2:{s:4:"text";s:11:"katzenmilch";s:5:"value";s:11:"katzenmilch";}i:16;a:2:{s:4:"text";s:8:"kr_theme";s:5:"value";s:8:"kr_theme";}i:17;a:2:{s:4:"text";s:6:"kuroir";s:5:"value";s:6:"kuroir";}i:18;a:2:{s:4:"text";s:9:"merbivore";s:5:"value";s:9:"merbivore";}i:19;a:2:{s:4:"text";s:14:"merbivore_soft";s:5:"value";s:14:"merbivore_soft";}i:20;a:2:{s:4:"text";s:15:"mono_industrial";s:5:"value";s:15:"mono_industrial";}i:21;a:2:{s:4:"text";s:7:"monokai";s:5:"value";s:7:"monokai";}i:22;a:2:{s:4:"text";s:14:"pastel_on_dark";s:5:"value";s:14:"pastel_on_dark";}i:23;a:2:{s:4:"text";s:14:"solarized_dark";s:5:"value";s:14:"solarized_dark";}i:24;a:2:{s:4:"text";s:15:"solarized_light";s:5:"value";s:15:"solarized_light";}i:25;a:2:{s:4:"text";s:9:"sqlserver";s:5:"value";s:9:"sqlserver";}i:26;a:2:{s:4:"text";s:8:"terminal";s:5:"value";s:8:"terminal";}i:27;a:2:{s:4:"text";s:8:"textmate";s:5:"value";s:8:"textmate";}i:28;a:2:{s:4:"text";s:8:"tomorrow";s:5:"value";s:8:"tomorrow";}i:29;a:2:{s:4:"text";s:14:"tomorrow_night";s:5:"value";s:14:"tomorrow_night";}i:30;a:2:{s:4:"text";s:19:"tomorrow_night_blue";s:5:"value";s:19:"tomorrow_night_blue";}i:31;a:2:{s:4:"text";s:21:"tomorrow_night_bright";s:5:"value";s:21:"tomorrow_night_bright";}i:32;a:2:{s:4:"text";s:23:"tomorrow_night_eighties";s:5:"value";s:23:"tomorrow_night_eighties";}i:33;a:2:{s:4:"text";s:8:"twilight";s:5:"value";s:8:"twilight";}i:34;a:2:{s:4:"text";s:11:"vibrant_ink";s:5:"value";s:11:"vibrant_ink";}i:35;a:2:{s:4:"text";s:5:"xcode";s:5:"value";s:5:"xcode";}}s:5:"value";s:6:"chrome";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:13:"CodeMirrorSrc";a:7:{s:4:"name";s:13:"CodeMirrorSrc";s:4:"desc";s:81:"Toss in latest CodeMirror CDN or local url...never be outdated again! Hurray..!!!";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:57:"https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:15:"CodeMirrorTHEME";a:7:{s:4:"name";s:15:"CodeMirrorTHEME";s:4:"desc";s:73:"45 themes to work with, knock yourself out! Surely there is one you like?";s:4:"type";s:4:"list";s:7:"options";a:46:{i:0;a:2:{s:4:"text";s:7:"(empty)";s:5:"value";s:0:"";}i:1;a:2:{s:4:"text";s:8:"3024-day";s:5:"value";s:8:"3024-day";}i:2;a:2:{s:4:"text";s:10:"3024-night";s:5:"value";s:10:"3024-night";}i:3;a:2:{s:4:"text";s:6:"abcdef";s:5:"value";s:6:"abcdef";}i:4;a:2:{s:4:"text";s:15:"ambiance-mobile";s:5:"value";s:15:"ambiance-mobile";}i:5;a:2:{s:4:"text";s:8:"ambiance";s:5:"value";s:8:"ambiance";}i:6;a:2:{s:4:"text";s:11:"base16-dark";s:5:"value";s:11:"base16-dark";}i:7;a:2:{s:4:"text";s:12:"base16-light";s:5:"value";s:12:"base16-light";}i:8;a:2:{s:4:"text";s:6:"bespin";s:5:"value";s:6:"bespin";}i:9;a:2:{s:4:"text";s:10:"blackboard";s:5:"value";s:10:"blackboard";}i:10;a:2:{s:4:"text";s:6:"cobalt";s:5:"value";s:6:"cobalt";}i:11;a:2:{s:4:"text";s:10:"colorforth";s:5:"value";s:10:"colorforth";}i:12;a:2:{s:4:"text";s:7:"dracula";s:5:"value";s:7:"dracula";}i:13;a:2:{s:4:"text";s:7:"eclipse";s:5:"value";s:7:"eclipse";}i:14;a:2:{s:4:"text";s:7:"elegant";s:5:"value";s:7:"elegant";}i:15;a:2:{s:4:"text";s:11:"erlang-dark";s:5:"value";s:11:"erlang-dark";}i:16;a:2:{s:4:"text";s:9:"hopscotch";s:5:"value";s:9:"hopscotch";}i:17;a:2:{s:4:"text";s:8:"icecoder";s:5:"value";s:8:"icecoder";}i:18;a:2:{s:4:"text";s:7:"isotope";s:5:"value";s:7:"isotope";}i:19;a:2:{s:4:"text";s:11:"lesser-dark";s:5:"value";s:11:"lesser-dark";}i:20;a:2:{s:4:"text";s:9:"liquibyte";s:5:"value";s:9:"liquibyte";}i:21;a:2:{s:4:"text";s:8:"material";s:5:"value";s:8:"material";}i:22;a:2:{s:4:"text";s:3:"mbo";s:5:"value";s:3:"mbo";}i:23;a:2:{s:4:"text";s:8:"mdn-like";s:5:"value";s:8:"mdn-like";}i:24;a:2:{s:4:"text";s:8:"midnight";s:5:"value";s:8:"midnight";}i:25;a:2:{s:4:"text";s:7:"monokai";s:5:"value";s:7:"monokai";}i:26;a:2:{s:4:"text";s:4:"neat";s:5:"value";s:4:"neat";}i:27;a:2:{s:4:"text";s:3:"neo";s:5:"value";s:3:"neo";}i:28;a:2:{s:4:"text";s:5:"night";s:5:"value";s:5:"night";}i:29;a:2:{s:4:"text";s:12:"paraiso-dark";s:5:"value";s:12:"paraiso-dark";}i:30;a:2:{s:4:"text";s:13:"paraiso-light";s:5:"value";s:13:"paraiso-light";}i:31;a:2:{s:4:"text";s:14:"pastel-on-dark";s:5:"value";s:14:"pastel-on-dark";}i:32;a:2:{s:4:"text";s:10:"railscasts";s:5:"value";s:10:"railscasts";}i:33;a:2:{s:4:"text";s:8:"rubyblue";s:5:"value";s:8:"rubyblue";}i:34;a:2:{s:4:"text";s:4:"seti";s:5:"value";s:4:"seti";}i:35;a:2:{s:4:"text";s:9:"solarized";s:5:"value";s:9:"solarized";}i:36;a:2:{s:4:"text";s:10:"the-matrix";s:5:"value";s:10:"the-matrix";}i:37;a:2:{s:4:"text";s:21:"tomorrow-night-bright";s:5:"value";s:21:"tomorrow-night-bright";}i:38;a:2:{s:4:"text";s:23:"tomorrow-night-eighties";s:5:"value";s:23:"tomorrow-night-eighties";}i:39;a:2:{s:4:"text";s:4:"ttcn";s:5:"value";s:4:"ttcn";}i:40;a:2:{s:4:"text";s:8:"twilight";s:5:"value";s:8:"twilight";}i:41;a:2:{s:4:"text";s:11:"vibrant-ink";s:5:"value";s:11:"vibrant-ink";}i:42;a:2:{s:4:"text";s:7:"xq-dark";s:5:"value";s:7:"xq-dark";}i:43;a:2:{s:4:"text";s:8:"xq-light";s:5:"value";s:8:"xq-light";}i:44;a:2:{s:4:"text";s:4:"yeti";s:5:"value";s:4:"yeti";}i:45;a:2:{s:4:"text";s:7:"zenburn";s:5:"value";s:7:"zenburn";}}s:5:"value";s:7:"elegant";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:23:"activateAceOrCodeMirror";a:7:{s:4:"name";s:23:"activateAceOrCodeMirror";s:4:"desc";s:528:"If not set to NONE, this plugin will set TinymceWrapper as default element code editor, and thus use Ace or CodeMirror for whatever file/element textareas (including quick update/create) that you specify in the TinymceWrapperCodeMirror chunk. This takes the chunkSuffix as well. Please set this well inorder not to conflict with TinyMCE RTE. And yes, you can use TinyMCE RTE and Ace or CodeMirror same time, one for content, the other for TVs or quick update...have fun! This is also compatible with twCodeMirror.js and twAce.js";s:4:"type";s:4:"list";s:7:"options";a:3:{i:0;a:2:{s:4:"text";s:3:"Ace";s:5:"value";s:3:"Ace";}i:1;a:2:{s:4:"text";s:10:"CodeMirror";s:5:"value";s:10:"CodeMirror";}i:2;a:2:{s:4:"text";s:4:"none";s:5:"value";s:0:"";}}s:5:"value";s:3:"Ace";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:36:"activateAceOrCodeMirrorOnNewResource";a:7:{s:4:"name";s:36:"activateAceOrCodeMirrorOnNewResource";s:4:"desc";s:90:"New Resources have the option of a code editor. Respects activateAceOrCodeMirrorOnRichText";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:33:"activateAceOrCodeMirrorOnRichText";a:7:{s:4:"name";s:33:"activateAceOrCodeMirrorOnRichText";s:4:"desc";s:171:"Prevent Ace or CodeMirror from ever firing when Rich Text is turned on for a particular resource. Respects activateAceOrCodeMirrorOnNewResource and system richtext_default";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:28:"useAceOrCodeMirrorEveryWhere";a:7:{s:4:"name";s:28:"useAceOrCodeMirrorEveryWhere";s:4:"desc";s:368:"Experimental - Fires at OnManagerPageInit. Works Manager-wide. No need to be editing a MODX resource or element to load Ace or CodeMirror. You can be at the Dashboard or CMP to use Code Editor- comes in handy when doing Quick Update/Create outside of Resource and elements/files. This option respects useAceOrCodeMirrorOnResources and useAceOrCodeMirrorOnElementsFiles";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:33:"useAceOrCodeMirrorOnElementsFiles";a:7:{s:4:"name";s:33:"useAceOrCodeMirrorOnElementsFiles";s:4:"desc";s:72:"Activate Manager pages of Chunks, Snippets, Plugins, Templates and Files";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:29:"useAceOrCodeMirrorOnResources";a:7:{s:4:"name";s:29:"useAceOrCodeMirrorOnResources";s:4:"desc";s:41:"You can turn this on or off for Resources";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:7:"Content";a:7:{s:4:"name";s:7:"Content";s:4:"desc";s:36:"Transform Resource Content textarea?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:2:{i:0;a:2:{s:4:"text";s:3:"Yes";s:5:"value";s:3:"Yes";}i:1;a:2:{s:4:"text";s:2:"No";s:5:"value";s:2:"No";}}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:11:"Description";a:7:{s:4:"name";s:11:"Description";s:4:"desc";s:31:"Transform Description textarea?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:2:{i:0;a:2:{s:4:"text";s:3:"Yes";s:5:"value";s:3:"Yes";}i:1;a:2:{s:4:"text";s:2:"No";s:5:"value";s:2:"No";}}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:9:"Introtext";a:7:{s:4:"name";s:9:"Introtext";s:4:"desc";s:29:"Transform Introtext textarea?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:2:{i:0;a:2:{s:4:"text";s:3:"Yes";s:5:"value";s:3:"Yes";}i:1;a:2:{s:4:"text";s:2:"No";s:5:"value";s:2:"No";}}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:3:"TVs";a:7:{s:4:"name";s:3:"TVs";s:4:"desc";s:28:"Transform Rich TVs textarea?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:12:"fileImageTVs";a:7:{s:4:"name";s:12:"fileImageTVs";s:4:"desc";s:137:"You will be able to use elFinder,  Responsive FileManager, or the other custom browsers to input data in your File and Image TVs, hurray!";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:17:"quickUpdateCreate";a:7:{s:4:"name";s:17:"quickUpdateCreate";s:4:"desc";s:162:"Use TinyMCE to edit/create as many resources as you want at the same time, in the same browser window, thanks to MODX Quick Update/Create and TinyMCE flexibility.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:15:"autoFileBrowser";a:7:{s:4:"name";s:15:"autoFileBrowser";s:4:"desc";s:48:"Please select which awesome file browser to use.";s:4:"type";s:4:"list";s:7:"options";a:4:{i:0;a:2:{s:4:"text";s:17:"modxNativeBrowser";s:5:"value";s:17:"modxNativeBrowser";}i:1;a:2:{s:4:"text";s:15:"elFinderBrowser";s:5:"value";s:15:"elFinderBrowser";}i:2;a:2:{s:4:"text";s:28:"responsivefilemanagerBrowser";s:5:"value";s:28:"responsivefilemanagerBrowser";}i:3;a:2:{s:4:"text";s:18:"roxyFilemanBrowser";s:5:"value";s:18:"roxyFilemanBrowser";}}s:5:"value";s:15:"elFinderBrowser";s:7:"lexicon";N;s:4:"area";s:17:"02 Browser Config";}s:20:"browserTopNAVsubtext";a:7:{s:4:"name";s:20:"browserTopNAVsubtext";s:4:"desc";s:55:"Slogan to appear in your Manager Top Nav dropdown menu.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:24:"Wonderfully Manage Media";s:7:"lexicon";N;s:4:"area";s:17:"02 Browser Config";}s:31:"replaceDefaultFileImageTVbutton";a:7:{s:4:"name";s:31:"replaceDefaultFileImageTVbutton";s:4:"desc";s:86:"When using a custom browser, you may suppress MODX native browser file/image TV button";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:17:"02 Browser Config";}s:23:"elFinderBrowserRTEtitle";a:7:{s:4:"name";s:23:"elFinderBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:24:"elFinder Awesome Browser";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:21:"elFinderBrowserRTEurl";a:7:{s:4:"name";s:21:"elFinderBrowserRTEurl";s:4:"desc";s:83:"Something like elfinder.html?unlocked=1&amp;rememberLastDir=1&amp;defaultView=icons";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:233:"[[~[[TinymceWrapperGRF? &amp;knownField=`pagetitle` &amp;kF=`pagetitle` &amp;kFv=`tw_elfinder_browser` &amp;gNuFv=`id`]]? &amp;scheme=`full` &amp;rememberLastDir=`1` &amp;defaultView=`icons` &amp;unlocked=`1` &amp;theme=`windows10`]]";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:25:"elFinderBrowserSHORTtitle";a:7:{s:4:"name";s:25:"elFinderBrowserSHORTtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:8:"elFinder";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:26:"elFinderBrowserTopNAVtitle";a:7:{s:4:"name";s:26:"elFinderBrowserTopNAVtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:21:"elFinder File Browser";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:24:"elFinderBrowserTopNAVurl";a:7:{s:4:"name";s:24:"elFinderBrowserTopNAVurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:233:"[[~[[TinymceWrapperGRF? &amp;knownField=`pagetitle` &amp;kF=`pagetitle` &amp;kFv=`tw_elfinder_browser` &amp;gNuFv=`id`]]? &amp;scheme=`full` &amp;rememberLastDir=`1` &amp;defaultView=`icons` &amp;unlocked=`1` &amp;theme=`windows10`]]";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:26:"modxNativeBrowserQuirkMode";a:7:{s:4:"name";s:26:"modxNativeBrowserQuirkMode";s:4:"desc";s:71:"Load MODX File Browser the native way - thanks to Denis from dyranov.ru";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:26:"04 Browsers - MODx Browser";}s:25:"modxNativeBrowserRTEtitle";a:7:{s:4:"name";s:25:"modxNativeBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:24:"MODx Native File Browser";s:7:"lexicon";N;s:4:"area";s:26:"04 Browsers - MODx Browser";}s:23:"modxNativeBrowserRTEurl";a:7:{s:4:"name";s:23:"modxNativeBrowserRTEurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:123:"MODx.config["manager_url"] + "index.php?a=" + MODx.action["browser"] + "&amp;source=" + MODx.config["default_media_source"]";s:7:"lexicon";N;s:4:"area";s:26:"04 Browsers - MODx Browser";}s:30:"modxNativeBrowserTopNAVpresent";a:7:{s:4:"name";s:30:"modxNativeBrowserTopNAVpresent";s:4:"desc";s:129:"If YES, the MODX native browser link will always show in the Top Nav. If NO, it will only show if a custom browser is not in use.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:26:"04 Browsers - MODx Browser";}s:36:"responsivefilemanagerBrowserRTEtitle";a:7:{s:4:"name";s:36:"responsivefilemanagerBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:22:"Responsive FileManager";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:34:"responsivefilemanagerBrowserRTEurl";a:7:{s:4:"name";s:34:"responsivefilemanagerBrowserRTEurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:103:"MODx.config.assets_url+"components/tinymcewrapper/responsivefilemanager/filemanager/dialog.php?xtype=1"";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:38:"responsivefilemanagerBrowserSHORTtitle";a:7:{s:4:"name";s:38:"responsivefilemanagerBrowserSHORTtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:3:"RFM";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:39:"responsivefilemanagerBrowserTopNAVtitle";a:7:{s:4:"name";s:39:"responsivefilemanagerBrowserTopNAVtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:22:"Responsive FileManager";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:37:"responsivefilemanagerBrowserTopNAVurl";a:7:{s:4:"name";s:37:"responsivefilemanagerBrowserTopNAVurl";s:4:"desc";s:23:"Has no ?popup parameter";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:105:"MODx.config.assets_url+\\\'components/tinymcewrapper/responsivefilemanager/filemanager/dialog.php?xtype=0\\\'";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:26:"roxyFilemanBrowserRTEtitle";a:7:{s:4:"name";s:26:"roxyFilemanBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"Roxy Fileman";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}s:24:"roxyFilemanBrowserRTEurl";a:7:{s:4:"name";s:24:"roxyFilemanBrowserRTEurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:72:"MODx.config.assets_url+"components/tinymcewrapper/roxy/fileman/roxy.php"";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}s:28:"roxyFilemanBrowserSHORTtitle";a:7:{s:4:"name";s:28:"roxyFilemanBrowserSHORTtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:4:"Roxy";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}s:29:"roxyFilemanBrowserTopNAVtitle";a:7:{s:4:"name";s:29:"roxyFilemanBrowserTopNAVtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"Roxy Fileman";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}s:27:"roxyFilemanBrowserTopNAVurl";a:7:{s:4:"name";s:27:"roxyFilemanBrowserTopNAVurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:74:"MODx.config.assets_url+\\\'components/tinymcewrapper/roxy/fileman/roxy.php\\\'";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}}',
      'disabled' => 0,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '/*TinymceWrapper transforms all MODX native and non-native textareas (introtext, description, content, RichTVs, File/Image TVs, Quick Update/Create, MIGX TVs etc etc)

plugin fires at
OnRichTextEditorRegister
OnDocFormPrerender 

(optionally)
OnTempFormPrerender,OnSnipFormPrerender,OnChunkFormPrerender,OnPluginFormPrerender,OnFileEditFormPrerender

OnManagerPageInit
  FOR ALL ROUND ENJOYMENT THROUGH OUT THE MANAGER
  1. for (quick update/create resources & elements (for Ace or CodeMirror),
  2. for top nav media link for elFinder, Responsive FileManager and Roxy custom file browsers,


-------------------Roadmap:
-Create more beautiful themes;
-Look for more ways to be awesome;
---------------------------

Dedicated to those who have cried
---------------------------

http://www.leofec.com/modx-revolution/
-donshakespeare in the MODx forum
To God, almighty, be all the glory.
*/

$modx->getService(\'error\',\'error.modError\', \'\', \'\');
$modxEventName = $modx->event->name;
//let us tell System Settings that we have a new RTEditor
if ($modxEventName == \'OnRichTextEditorRegister\') {
  $modx->event->output(\'TinymceWrapper\');
  return;
}

//let us get MODx browser callback ready to fire
if ($modxEventName == \'OnRichTextBrowserInit\' && $autoFileBrowser == \'modxNativeBrowser\') {
 $modx->controller->addJavascript(MODX_ASSETS_URL.\'components/tinymcewrapper/browserConnectors/browser.js\');
  $modx->event->output(\'twBrowserCallback\');
  return;
}

//whether the user has RTE enabled in System Settings
$useEditor = $modx->getOption(\'use_editor\');
//is our awesome wrapper the set one?
$whichEditor = $modx->getOption(\'which_editor\');
$whichElementEditor = $modx->getOption(\'which_element_editor\');
//whether the user has RTE set to default for all resources in System Settings
$richtext_default = $modx->getOption(\'richtext_default\');

$sp = $scriptProperties;
//let\'s grab a few things from our plugin\'s defualt properties property set
$activate = $modx->getOption(\'activateTinyMCE\', $sp);
$activateAceOrCodeMirror = $modx->getOption(\'activateAceOrCodeMirror\', $sp);
$useAceOrCodeMirrorEveryWhere = $modx->getOption(\'useAceOrCodeMirrorEveryWhere\', $sp);
$useAceOrCodeMirrorOnElementsFiles = $modx->getOption(\'useAceOrCodeMirrorOnElementsFiles\', $sp);
$useAceOrCodeMirrorOnResources = $modx->getOption(\'useAceOrCodeMirrorOnResources\', $sp);
$activateAceOrCodeMirrorOnNewResource = $modx->getOption(\'activateAceOrCodeMirrorOnNewResource\', $sp);
$activateAceOrCodeMirrorOnRichText = $modx->getOption(\'activateAceOrCodeMirrorOnRichText\', $sp);
$AceTHEME = $modx->getOption(\'AceTHEME\', $sp);
$AceSrc = $modx->getOption(\'AceSrc\', $sp);
$CodeMirrorTHEME = $modx->getOption(\'CodeMirrorTHEME\', $sp);
$CodeMirrorSrc = $modx->getOption(\'CodeMirrorSrc\', $sp);
$jQuerySrc = $modx->getOption(\'jQuery\', $sp);
$tinySrc = $modx->getOption(\'tinySrc\', $sp);
$newResources = $modx->getOption(\'newResources\', $sp);
$introtext = $modx->getOption(\'Introtext\', $sp);
$intro = \'\';
$description = $modx->getOption(\'Description\', $sp);
$desc = \'\';
$content = $modx->getOption(\'Content\', $sp);
$con = \'\';
$tvs = $modx->getOption(\'TVs\', $sp);
$tvAddict = $modx->getOption(\'tvAddict\', $sp);
$tvSuperAddict = $modx->getOption(\'tvSuperAddict\', $sp);
$autoFileBrowser = $modx->getOption(\'autoFileBrowser\', $sp);
$browserTopNAVsubtext = $modx->getOption(\'browserTopNAVsubtext\', $sp);
$fileImageTVs = $modx->getOption(\'fileImageTVs\', $sp);
$browserTVs = \'\';
$disable = $modx->getOption(\'disableEnableCheckbox\', $sp);
//if a suffix is entered, all the chunks in use must have the same suffix. (e.g. TinymceWrapperIntrotext-suff, TinymceWrapperDescription-suff,TinymceWrapperContent-suff,TinymceWrapperTvs-suff)
$suffix = $modx->getOption(\'chunkSuffix\', $sp);
$fileManagerTopNavLink = $modx->getOption(\'fileManagerTopNavLink\', $sp);
$fileManagerTopNavModal = $modx->getOption(\'fileManagerTopNavModal\', $sp);
$fileManagerTopNavModalSkin = $modx->getOption(\'fileManagerTopNavModalSkin\', $sp);
$fileManagerTopNavModalSkin = $fileManagerTopNavModalSkin ? : \'""\';

//grab file browser options
$modxNativeBrowserRTEurl = $modx->getOption(\'modxNativeBrowserRTEurl\', $sp);
$modxNativeBrowserRTEtitle = $modx->getOption(\'modxNativeBrowserRTEtitle\', $sp);
$modxNativeBrowserTopNAVpresent = $modx->getOption(\'modxNativeBrowserTopNAVpresent\', $sp);
$modxNativeBrowserQuirkMode = $modx->getOption(\'modxNativeBrowserQuirkMode\', $sp);

$replaceDefaultFileImageTVbutton = $modx->getOption(\'replaceDefaultFileImageTVbutton\', $sp) ? : 0;

$elFinderBrowserRTEurl = $modx->getOption(\'elFinderBrowserRTEurl\', $sp);
$elFinderBrowserRTEtitle = $modx->getOption(\'elFinderBrowserRTEtitle\', $sp);
$elFinderBrowserTopNAVurl = $modx->getOption(\'elFinderBrowserTopNAVurl\', $sp);
$elFinderBrowserTopNAVtitle = $modx->getOption(\'elFinderBrowserTopNAVtitle\', $sp);
$elFinderBrowserSHORTtitle = $modx->getOption(\'elFinderBrowserSHORTtitle\', $sp);

$responsivefilemanagerBrowserRTEurl = $modx->getOption(\'responsivefilemanagerBrowserRTEurl\', $sp);
$responsivefilemanagerBrowserRTEtitle = $modx->getOption(\'responsivefilemanagerBrowserRTEtitle\', $sp);
$responsivefilemanagerBrowserTopNAVurl = $modx->getOption(\'responsivefilemanagerBrowserTopNAVurl\', $sp);
$responsivefilemanagerBrowserTopNAVtitle = $modx->getOption(\'responsivefilemanagerBrowserTopNAVtitle\', $sp);
$responsivefilemanagerBrowserSHORTtitle = $modx->getOption(\'responsivefilemanagerBrowserSHORTtitle\', $sp);

$roxyFilemanBrowserRTEtitle = $modx->getOption(\'roxyFilemanBrowserRTEtitle\', $sp);
$roxyFilemanBrowserRTEurl = $modx->getOption(\'roxyFilemanBrowserRTEurl\', $sp);
$roxyFilemanBrowserTopNAVurl = $modx->getOption(\'roxyFilemanBrowserTopNAVurl\', $sp);
$roxyFilemanBrowserTopNAVtitle = $modx->getOption(\'roxyFilemanBrowserTopNAVtitle\', $sp);
$roxyFilemanBrowserSHORTtitle = $modx->getOption(\'roxyFilemanBrowserSHORTtitle\', $sp);

//grab gallery settings
$enableImageGallery = $modx->getOption(\'enableImageGallery\', $sp);
$tinyJSONGalleryTABtitle = $modx->getOption(\'tinyJSONGalleryTABtitle\', $sp) ? : "JSON Image Gallery";
$tinyJSONGalleryTABposition = $modx->getOption(\'tinyJSONGalleryTABposition\', $sp) ? : 0;
$imageGalleryIDs = $modx->getOption(\'imageGalleryIDs\', $sp);
$galleryChunkNameKey = $modx->getOption(\'galleryChunkNameKey\', $sp);
$TinyJSONGalleryTV = $modx->getOption(\'TinyJSONGalleryTV\', $sp) ?:"TinyJSONGalleryTV";
$galleryJSfile = $modx->getOption(\'galleryJSfile\', $sp);

//grab 3rd party TinyMCE inits
$customJS = $modx->getOption(\'customJS\', $sp);
$customJSchunks = $modx->getOption(\'customJSchunks\', $sp);

//this eliminates clutter and confusion: reusuable config is the way of the past and the future - code here will be put in placeholder [[+commonTinyMCECode]]
//apply comma here, not in the chunk calling it --na na, make user leave trailing comma in commonCode Chunk

if ($enableImageGallery == 1) {
  if ($modxEventName == \'OnChunkFormPrerender\' || $modxEventName == \'OnDocFormPrerender\') {
    $galleryIsGolden = 0;
    if ($modxEventName == \'OnChunkFormPrerender\') {
      if($id){
        $thisChunkId = $id;
        $imageGalleryIDsTrue = \'\';
        if($imageGalleryIDs){
          $imageGalleryIDs = preg_replace(\'/\\s+/\', \'\', $imageGalleryIDs);
          $imageGalleryIDs = preg_replace("/[^a-z0-9,-_]+/i", \' \', $imageGalleryIDs);
          $imageGalleryIDs = explode(\',\', $imageGalleryIDs);
          if(in_array($thisChunkId, $imageGalleryIDs)) {
            $imageGalleryIDsTrue = 1;
          }
        }
        $chS = $modx->getObject("modChunk", $thisChunkId);
        $ch = $chS->get(\'name\');
        $chunkGalleryVal = $chS->get(\'content\');
        // if(in_array($thisChunkId, $imageGalleryIDs) || substr($ch, 0, strlen($galleryChunkNameKey)) === $galleryChunkNameKey && $enableImageGallery) {
        if($imageGalleryIDsTrue || strpos($ch, $galleryChunkNameKey) !== false && $enableImageGallery) {
          $modx->regClientStartupHTMLBlock(\'
            <script type="text/javascript">
              var extjsPanelTabs = "modx-chunk-tabs";
              var textareaForJSON = "modx-chunk-snippet";
              var textareaForJSONbk = "modx-chunk-snippet";
              var tinyJSONGalleryGalButtons = "#modx-action-buttons .x-toolbar-left-row";
              var tvChunkGalleryVal = \'.json_encode($chunkGalleryVal).\';
              var backendOrfrontendGallery = "backend";
              var tinyJSONGalleryTABtitle = "\'.$tinyJSONGalleryTABtitle.\'";
              var tinyJSONGalleryTABposition = \'.$tinyJSONGalleryTABposition.\';
              var modxGalleryAssetsUrl = MODx.config.assets_url;
              var galleryBackUpRTEskin = \'.$fileManagerTopNavModalSkin.\';
            </script>
          \');
          $galleryIsGolden = 1;
        }
      }
    }
    if ($modxEventName == \'OnDocFormPrerender\' && $id) {
      if($tvName = $modx->getObject(\'modTemplateVar\', array(\'name\' =>$TinyJSONGalleryTV))){
        $tvId = $tvName->get(\'id\');
        $tvGalleryVal = $resource->getTVValue($tvId);
        // $tvTemplateId = $modx->getObject(\'modTemplateVarTemplate\', array("tmplvarid" => $tvId))->get("templateid");
        if($resourceTemplateId = $resource->get("template")){
          if($tvTemplateId = $modx->getObject(\'modTemplateVarTemplate\', array("tmplvarid" => $tvId)) ){
            $tvTemplateId = $tvTemplateId->get("templateid");
            if ($tvGalleryVal || $tvGalleryVal == \'\'){
              if ($tvTemplateId == $resourceTemplateId) {
                $modx->regClientStartupHTMLBlock(\'
                  <script type="text/javascript">
                    var extjsPanelTabs = "modx-resource-tabs";
                    var textareaForJSON = "tv\'.$tvId.\'";
                    var textareaForJSONbk = "tv\'.$tvId.\'";
                    var tinyJSONGalleryGalButtons = "#modx-action-buttons .x-toolbar-left-row";
                    var tvChunkGalleryVal = \'.json_encode($tvGalleryVal).\';
                    var backendOrfrontendGallery = "backend";
                    var tinyJSONGalleryTABtitle = "\'.$tinyJSONGalleryTABtitle.\'";
                    var tinyJSONGalleryTABposition = \'.$tinyJSONGalleryTABposition.\';
                    var modxGalleryAssetsUrl = MODx.config.assets_url;
                    var galleryBackUpRTEskin = \'.$fileManagerTopNavModalSkin.\';
                  </script>
                \');
                $galleryIsGolden = 1;
              }
            }
          }
        }//////
      }
    }
    if($galleryIsGolden == 1){
      if($galleryJSfile){
        $modx->regClientStartupHTMLBlock("<script src=\'" . $galleryJSfile . "\'\'></script>");
      }
      else{
        $modx->regClientStartupHTMLBlock("<script src=\'" . MODX_ASSETS_URL . "components/tinymcewrapper/gallery/js/TinyJSONGallery.js\'></script>");
      }
    }
  }
}


if ($modxEventName == \'OnManagerPageInit\' || $modxEventName == \'OnDocFormPrerender\') {
  $commonCode = $modx->getChunk(\'TinymceWrapperCommonCode\' . $suffix);
  $commonCode = $commonCode ? $commonCode : \'\';
}

//Quick and dirty hack to allow any and all other 3rd party Extras use TinyMCE


if ($modxEventName == \'OnManagerPageInit\' && $customJS && $customJSchunks) {
  function listArray($thisList){
    $thisList = preg_replace(\'/\\s+/\', \'\', $thisList);
    $thisList = preg_replace("/[^a-z0-9,-_]+/i", \' \', $thisList);
    $thisList = explode(\',\', $thisList);
    return $thisList;
  }
  $getCustomJSchunks = "";
  $customJSchunk = listarray($customJSchunks);
  $i = 0;
  foreach ($customJSchunk as $c) {
    $customJSchunk[$i] = $modx->getChunk(\'TinymceWrapper\'.$c.$suffix)."\\n";
    $getCustomJSchunks.= $customJSchunk[$i];
    $i++;
  }
  $modx->regClientStartupHTMLBlock("<script>" . $getCustomJSchunks . "</script>");
}

//when TinyMCE is temporarily disabled, any new edit is updated before saving
$autoSaveTextAreas = \'
  function autoSaveTextAreas(selectorId){
    setTimeout(function(){
      $("#"+selectorId).on("change", function() {
        tinyMCE.get(selectorId).setContent($("#"+selectorId).val())
        // console.log(selectorId+" has been updated");//debug stuff
      })
    },500)
   }
\';

//let\'s setup some functions and file select callbacks for our supported file browsers
switch ($autoFileBrowser) {
  case \'modxNativeBrowser\':
    $browserRTEurl = $modxNativeBrowserRTEurl;
    $browserRTEtitle = $modxNativeBrowserRTEtitle;
    break;
  case \'elFinderBrowser\':
    $browserRTEurl = \'"\'.$elFinderBrowserRTEurl.\'"\';
    $browserRTEtitle = $elFinderBrowserRTEtitle;
    $browserTopNAVurl = \'\\\'\'.$elFinderBrowserTopNAVurl.\'\\\'\';
    $browserTopNAVtitle = $elFinderBrowserTopNAVtitle;
    $browserShortTitle = $elFinderBrowserSHORTtitle;
    break;
  case \'responsivefilemanagerBrowser\':
    $browserRTEtitle = $responsivefilemanagerBrowserRTEtitle;
    $browserRTEurl = $responsivefilemanagerBrowserRTEurl;
    $browserTopNAVurl = $responsivefilemanagerBrowserTopNAVurl;
    $browserTopNAVtitle = $responsivefilemanagerBrowserTopNAVtitle;
    $browserShortTitle = $responsivefilemanagerBrowserSHORTtitle;
    break;
  case \'roxyFilemanBrowser\':
    $browserRTEtitle = $roxyFilemanBrowserRTEtitle;
    $browserRTEurl = $roxyFilemanBrowserRTEurl;
    $browserTopNAVurl = $roxyFilemanBrowserTopNAVurl;
    $browserTopNAVtitle = $roxyFilemanBrowserTopNAVtitle;
    $browserShortTitle = $roxyFilemanBrowserSHORTtitle;
    break;
}


if ($autoFileBrowser == \'responsivefilemanagerBrowser\') {
  $browserFunctions=\'
    function responsive_filemanager_callback(field_id){
      thisFieldVal = $("#"+field_id).val();
      thisFieldNum = field_id.split("er");
      $("input#tv"+thisFieldNum[1]).val(thisFieldVal);
      $("#tv-image-preview-"+thisFieldNum[1]+" img").attr("title","preview by native MODx Image Browser");
      $("#"+field_id).parents(".modx-tv").find(".twImagePreview").hide().attr("src",thisFieldVal).insertBefore("#tv-image-preview-"+thisFieldNum[1]).fadeIn("slow");
      tinyMCE.activeEditor.windowManager.close();
    }
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      resp = \'.$browserRTEurl.\';
      if (resp.indexOf("?") < 0) {
        resp += "?field_id=" + field_name;
      }
      else {
        resp += "&field_id=" + field_name;
      }
      // console.log(resp); //debug stuff
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: resp,
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2
      }, {
        // oninsert: function(url) {
        //   alert("rte") //debug
        //   win.document.getElementById(field_name).value = url;
        // }
      });
    return false;
      }
  \';
}
elseif ($autoFileBrowser == \'roxyFilemanBrowser\') {
  $browserFunctions=\'
    autoFileBrowser = \'.$autoFileBrowser.\';
      function \'.$autoFileBrowser.\'(field_name, url, type, win) {
        roxyFileman = \'.$browserRTEurl.\';
        if (roxyFileman.indexOf("?") < 0) {
          roxyFileman += "?type=" + type;
        }
        else {
          roxyFileman += "&type=" + type;
        }
        roxyFileman += "&input=" + field_name + "&value=" + win.document.getElementById(field_name).value;
        if(tinyMCE.activeEditor.settings.language){
          roxyFileman += "&langCode=" + tinyMCE.activeEditor.settings.language;
        }
        tinyMCE.activeEditor.windowManager.open({
          title: "\'.$browserRTEtitle.\'",
          url: roxyFileman,
          plugins: "media",
          width : window.innerWidth / 1.2,
          height : window.innerHeight / 1.2
        }, {
          oninsert: function(url) {
            win.document.getElementById(field_name).value = url;
          }
        });
      return false;
      }
  \';
}
//thanks to Denis 
elseif ($autoFileBrowser == \'modxNativeBrowser\' && $modxNativeBrowserQuirkMode) {
  $modx->regClientStartupHTMLBlock("<style>.modx-browser {z-index: 99999!important;}</style>");
  $browserFunctions=\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      var path = url.substring(0,url.lastIndexOf("/")+1);
      var w = MODx.load({
        xtype: "modx-browser",
        multiple: true,
        //If there is no path, use default
        openTo: path || \'.$modxNativeBrowserRTEurl.\',
        listeners: {
          "select": {fn:function(data) {
            win.document.getElementById(field_name).value = data.relativeUrl;
            MODx.fireEvent("select",data);
          },scope:this}
        }
      });
      w.show();
    }
  \';
}
elseif ($autoFileBrowser == \'modxNativeBrowser\') {
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: \'.$browserRTEurl.\',
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2,
        classes: "twAutoBrowser",
        onPostRender: function(){
          $(".mce-twAutoBrowser iframe").attr("id","twAutoBrowser").load(function(){
            var checkRTEbuttons = setInterval(function() {
              if ($("#twAutoBrowser").contents().find(".modx-browser-rte-buttons").length) {
                $("#twAutoBrowser").contents().find(".modx-browser-rte-buttons").hide();
                clearInterval(checkRTEbuttons);
              }
            }, 50);
          })
        },
      }, {
        oninsert: function(url) {
          win.document.getElementById(field_name).value = url;
        }
      });
    return false;
    }
  \';
}
else{
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: \'.$browserRTEurl.\',
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2
      }, {
        oninsert: function(url) {
          win.document.getElementById(field_name).value = url;
        }
      });
    return false;
    }
  \';
}

//what happens when you click the enable/disable checkbox
//also for MIGX TVs

$enableDisableTinyClick = \'
  function tinyTVcheck(editor) {
    
    if(tinymce.get(editor) && !tinyMCE.get(editor).getParam("twExoticMarkdownEditor",false)){
      autoSaveTextAreas(editor);
      if($("input[data-tiny="+editor+"]").is(":checked")){
        tinymce.get(editor).hide();
        $("input[data-tiny="+editor+"]").attr("title","Show TinyMCE");
      }
      else{
        tinymce.get(editor).show();
        $("input[data-tiny="+editor+"]").trigger("change").attr("title","Temporarily Hide TinyMCE");
      }
    }
    else{
      $("input[data-tiny="+editor+"]").remove();
      if(typeof tinymce !== "undefined"){
        tinymce.activeEditor.windowManager.alert("Not applicable here");
      }
      else{
        alert("Not applicable here");
      }
    }
  }
  // $(".tinyTVcheck").on("mouseup",function() {
  //   autoSaveTextAreas($(this).attr("data-tiny"));
  //   if (this.checked) {
  //     tinymce.get($(this).attr("data-tiny")).hide();
  //     $(this).trigger("change").attr("title","Enable TinyMCE");
  //   }
  //   else{
  //     tinymce.get($(this).attr("data-tiny")).show();
  //     $(this).trigger("change").attr("title","Disable TinyMCE");
  //   }
  //   });
\';

//lock the below to this event, to prevent spill over
if ($modxEventName == \'OnDocFormPrerender\') {
  $enableDisableTiny = \'\';
  //is the enable/disable TinyMCE option selected? If so, let\'s create all the buttons at once; this will be split later on. This is good for TVs that have default content, and user wishes to revert. Disable TinyMCE, then revert.
  //there are two $("#ta") below; don\'t ask me why the Articles\' Container/Child are has own thing going own here
  if ($disable == 1) {
  //prepend is better than append - you\'ll see!!!
    $enableDisableTiny = \'
    $("#modx-resource-introtext").parent().parent().prepend("<input data-tiny=\\\'modx-resource-introtext\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"modx-resource-introtext\\")\\\' />&nbsp;&nbsp;&nbsp;");@
    $("#modx-resource-description").parent().parent().prepend("<input data-tiny=\\\'modx-resource-description\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"modx-resource-description\\")\\\' />&nbsp;&nbsp;&nbsp;");@
    $("#ta").parents("#modx-resource-content").find(".x-panel-header-text").prepend("<input data-tiny=\\\'ta\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"ta\\")\\\' />&nbsp;&nbsp;&nbsp;");
    if($("#articles-box-publishing-information").length){
      $("#ta").parents(".contentblocks_replacement").find("label[for=ta]").prepend("<input data-tiny=\\\'ta\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"ta\\")\\\' />&nbsp;&nbsp;&nbsp;");
    }
    if($("#modx-resource-tabs__articles-tab-template").length){
      $("#modx-resource-header").append("<p id=\\\'tinyArtAlert\\\' style=\\\'width:70%;margin:0 auto;background-color:#32AB9A;padding:10px;border-radius:10px;color:white;text-align:center;\\\'><b>TinymceWrapper Raw Code Protection:</b><br>Check this Articles Container > Template [Tab] > Content, before saving.<br>Unchecking the box will not only disable but remove TinyMCE, thus protecting your code</p>");
      $("#ta").parent().parent().find("label[for=ta]").prepend("<input data-tiny=\\\'ta\\\' checked=\\\'checked\\\' title=\\\'Remove TinyMCE \\\' type=\\\'checkbox\\\' class=\\\'tinyTVchecky\\\' onmouseup=\\\'javascript:tinymce.get(\\"ta\\").remove();$(this).remove();$(\\"#tinyArtAlert\\").fadeOut().remove();\\\' />&nbsp;&nbsp;&nbsp;");
    }
  \';
  //let\'s split the enable/disable checkboxes so that they don\'t appear randomly or unexpectedly
  $enableDisableTiny = explode("@", $enableDisableTiny);
  }

  //All TVs are here nicely placed independent of strict conditions, just in case we want to activate TVS even when RTE is disabled for a particular resource
  if ($tvs == 1) {
    $tvsChunk = $modx->getChunk(\'TinymceWrapperTVs\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
    if ($tvsChunk) {
      //let\'s remove the checkboxes that enables/disables TinyMCE for the TVs
      //let\'s allow the TV reset button to work through TinyMCE, either enabled or disabled - textareas are updated .on change + the delay is neccesary since we are pseudo binding to existing click event
      if ($disable == 1) {
        $richTv = \'
          if($(".modx-richtext").length){
            $(".modx-richtext").css({"overflow": "auto", "width": "100%", "min-height": "100px", "resize": "vertical"});
            function updateReset(updateR){
              if(tinymce.get(updateR)){
                setTimeout(function(){
                  tinyMCE.get(updateR).setContent($("#"+updateR).val());
                  // console.log(updateR+" has been updated");//debug stuff
                },200)
              }
            }
            $.each($(".modx-richtext"), function() {
              var updateR = $(this).attr("id");
              $(this).parents(".modx-tv").find(".modx-tv-reset").attr("data-tiny",this.id).on("click", function(){
                updateReset($(this).attr("data-tiny"));
              });
              $(this).parent().parent().prepend("<input data-tiny=\\\'" + this.id + "\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' onmouseup=\\\'tinyTVcheck(\\""+this.id+"\\")\\\' />");
            });
            setTimeout(function(){
              \' . $tvsChunk . \'
            },1000);
          }
        \';
      } 
      else {
        $richTv = \'
          if($(".modx-richtext").length){
            function updateReset(updateR){
              setTimeout(function(){
                tinyMCE.get(updateR).setContent($("#"+updateR).val());
                // console.log(updateR+" has been updated");//debug stuff
              },200)
            }
            $.each($(".modx-richtext"), function() {
              var updateR = $(this).attr("id");
              $(this).parents(".modx-tv").find(".modx-tv-reset").attr("data-tiny",this.id).on("click", function(){
                updateReset($(this).attr("data-tiny"));
              });
            });
            setTimeout(function(){
              \' . $tvsChunk . \'
            },1000);
          }
        \';
      }
    }
  }
  if ($fileImageTVs == 1) {
    /*
    - append hidden input#tinyFileImageBrowser to the body so that we have at least one active editor, in case the user has disabled TinyMCE for all other textareas and TVs
    - do some magic: create the respective image and file twBrowser buttons with appropriate properties when the page is really ready
    - create rudimentary image prev something similar to the native MODx\' file browser
    - init hidden #tinyFileImageBrowser
    - Create tinymce #tinyFileImageBrowser on condition; give a definite CSS theme (only when one is not already loaded) to avoid overriding issues. Allow cross-browser amiability by setting to inline:true
    - add twBrowser menu button to MODx Media drop down - depends on the option fileImageTvs
    - NOTE - Roxy don\'t have a callback...no preview 
    */
    $browserTVs = \'
      function imageFileTVpop(field_name, url, type, win) {
        thisUrl = \'.$browserRTEurl.\';
        if (thisUrl.indexOf("dialog") > 0) {
            thisUrl = thisUrl.replace("popup=1", "popup=0");
          if (thisUrl.indexOf("?") < 0) {
            thisUrl += "?field_id="+field_name;
          }
          else {
            thisUrl += "&field_id="+field_name;
          }
        }
        if (thisUrl.indexOf("fileman") > 0) {
          if (thisUrl.indexOf("?") < 0) {
            thisUrl += "?type=" + type;
          }
          else {
            thisUrl += "&type=" + type;
          }
          thisUrl += "&input=" + field_name + "&value=" + document.getElementById(field_name).value;
        }

        tinyMCE.activeEditor.windowManager.open({
          title: "\'.$browserRTEtitle.\'",
          url: thisUrl,
          width : window.innerWidth / 1.2,
          height : window.innerHeight / 1.2
        }, {
          oninsert: function(url) {
            $("#"+field_name).val(url);
            thisFieldNum = field_name.split("er");
            $("input#tv"+thisFieldNum[1]).val(url);
            $("#tv-image-preview-"+thisFieldNum[1]+" img").hide().attr({"src":url, "title":"preview by \'.$browserShortTitle.\'"}).fadeIn();
            // $("#"+field_name).parents(".modx-tv").find(".twImagePreview").hide().attr("src",url).insertBefore("#tv-image-preview-"+thisFieldNum[1]).fadeIn("slow");
            tinyMCE.activeEditor.windowManager.close();
          }
        });
      return false;
      }
      Ext.onReady(function(){
        replaceDefaultFileImageTVbutton = \'.$replaceDefaultFileImageTVbutton.\';
        setTimeout(function(){
          if(!$("#tinyFileImageBrowser").length){
            $("body").append("<input id=\\\'tinyFileImageBrowser\\\' type=\\\'hidden\\\' value=\\\'\\\' />");
          }
           $("input[id^=tvbrowser]").each(function(){
              fileOrImage = $(this).parents(".modx-tv").find(".x-form-file-trigger").attr("id");
              if($("#"+fileOrImage).length){
                twImageFileOnClick = "imageFileTVpop($(this).attr(\\\'data-tiny\\\'))";
                twImageFileBtn = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;(all&nbsp;files)&nbsp;\\\';
                twImageFileBtnTitle = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;All-File&nbsp;Browser&nbsp;\\\';
                twImageClass = \\\'twImageFileBtnClass x-form-trigger x-form-file-trigger\\\';
                twImagePreview = "";
              }
              else{
                twImageFileOnClick = "imageFileTVpop($(this).attr(\\\'data-tiny\\\'))";
                twImageFileBtn = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;(images)&nbsp;\\\';
                twImageFileBtnTitle = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;Image-Only&nbsp;Browser&nbsp;\\\';
                twImagePreview = "<img class=\\\'twImagePreview\\\' title=\\\'preview by \'.$browserShortTitle.\' Image Browser\\\' src=\\\'\\\' style=\\\'width:100px;display:none;\\\' />";
                twImageClass = \\\'twImageFileBtnClass x-form-trigger x-form-image-trigger\\\';
              }
              if(replaceDefaultFileImageTVbutton == 1){
                $(this).parents(".modx-tv").find(".x-form-trigger").replaceWith("<div class=\\\'"+twImageClass+"\\\' data-tiny="+this.id+"  title="+twImageFileBtnTitle+" onclick="+twImageFileOnClick+"></div>"+twImagePreview);
              }
              else{
                $(this).parents(".x-form-item")
                .find(".modx-tv-caption")
                .parent()
                .prepend("<input class=\\\'twImageFileBtnClass x-form-field-wrap x-form-trigger\\\' data-tiny="+this.id+" type=\\\'button\\\' value="+twImageFileBtn+" title="+twImageFileBtnTitle+" onclick="+twImageFileOnClick+">"+twImagePreview);
              }
              if(tinymce.editors.length < 1){
                tinymce.init({
                  selector: "#tinyFileImageBrowser",
                  skin_url: \'.$fileManagerTopNavModalSkin.\',
                  inline:true,
                  forced_root_block : "",
                  force_br_newlines : false,
                  force_p_newlines : false
                })
              }
           })
        },1000);
      })
    \';
    if($autoFileBrowser ==\'modxNativeBrowser\'){
      $browserTVs = \'\';
    }
  }
}

//if user selects the option to activate Ace / CodeMirror, we save him/her the trip of heading to System Settings - is this being too officious or intrusive?
if ($activateAceOrCodeMirror) {
  $fireEditor = 0;
  if ($whichElementEditor !== \'TinymceWrapper\') {
    $whichEl = $modx->getObject(\'modSystemSetting\', \'which_element_editor\');
    $whichEl->set(\'value\', \'TinymceWrapper\');
    $whichEl->save();
  }
  $onlyElementsFiles = \'OnTempFormPrerender,OnSnipFormPrerender,OnChunkFormPrerender,OnPluginFormPrerender,OnFileEditFormPrerender\';
  $onlyElementsFiles = explode(\',\', $onlyElementsFiles);
  $twGetResourceContentType = "";
  // experimental - OnManagerPageInit or other ... would have been good but...
  if($useAceOrCodeMirrorEveryWhere){
    $updateResource = "resource/update";
    $newResource = "resource/create";
    $updateCreateElement = "element/";
    $updateCreateFile = "system/file/";
    $fireEditor = 1;

    // existing or new elements or files
    if (isset($_GET["a"]) && !$useAceOrCodeMirrorOnElementsFiles && (strpos($_GET["a"], $updateCreateElement) !== false || strpos($_GET["a"], $updateCreateFile) !== false)) {
        $fireEditor = 0;
    }

    // existing resource with RT value
    if (isset($_GET["a"], $_GET["id"]) && strpos($_GET["a"], $updateResource) !== false) {
      $twGetResourceContentType = $modx->getObject("modResource",$_GET["id"])->getOne(\'ContentType\')->get(\'mime_type\');
      if (!$activateAceOrCodeMirrorOnRichText && $modx->getObject("modResource",$_GET["id"])->get(\'richtext\')) {
        $fireEditor = 0;
      }
      if (!$useAceOrCodeMirrorOnResources) {
        $fireEditor = 0;
      }
    }
    // new resource with no RT value
    // if (isset($_GET["a"],$_GET["id"]) && $_GET["id"] == 0 && strpos($_GET["a"], $newResource) !== false) {
    if (isset($_GET["a"]) && strpos($_GET["a"], $newResource) !== false) { //expand criteria for Collection Extra and oter weird stuff
      if (!$activateAceOrCodeMirrorOnNewResource) {
        $fireEditor = 0;
      }
      // new resource with no RT value but System settings default RT value
      if ($activateAceOrCodeMirrorOnNewResource && $richtext_default == 1) {
        $fireEditor = 0;
      }
      if (!$useAceOrCodeMirrorOnResources) {
        $fireEditor = 0;
      }
    }
  }

  if($useAceOrCodeMirrorOnElementsFiles && in_array($modxEventName, $onlyElementsFiles)){
    $fireEditor = 1;
  }

  if($useAceOrCodeMirrorOnResources && $modxEventName == "OnDocFormPrerender"){
    $fireEditor = 1;
    // existing resource has contenttype
    if($id){
      $twGetResourceContentType = $resource->getOne(\'ContentType\')->get(\'mime_type\');
    }
    // existing resource with RT value
    if (!$activateAceOrCodeMirrorOnRichText && $id && $resource->get(\'richtext\')) {
      $fireEditor = 0;
    }
    // new resource with no RT value
    if (!$activateAceOrCodeMirrorOnNewResource && !$id) {
      $fireEditor = 0;
    }
    // new resource with no RT value but System settings default RT value
    if ($activateAceOrCodeMirrorOnNewResource && !$id && $richtext_default == 1) {
      $fireEditor = 0;
    }
    if ($useAceOrCodeMirrorEveryWhere) {
      $fireEditor = 0;
    }
  }

  //make sure that this never fires twice, once at OnManagerPageInit and other events.
  if($fireEditor == 1){
    if ($activateTinyMCE !== 1 && $tvSuperAddict !== 1 && $jQuerySrc) {
      $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
    }
    if ($activateAceOrCodeMirror == "Ace") {
      $editorOutput= $modx->getChunk(\'TinymceWrapperAce\'.$chunkSuffix, array(\'AceSrc\' => $AceSrc, \'AceTHEME\' => $AceTHEME));
      $modx->regClientStartupScript($AceSrc.\'ace.js\');
    }
    else{
      $editorOutput= $modx->getChunk(\'TinymceWrapperCodeMirror\'.$chunkSuffix, array(\'CodeMirrorSrc\' => $CodeMirrorSrc, \'CodeMirrorTHEME\' => $CodeMirrorTHEME));
      $modx->regClientStartupScript($CodeMirrorSrc.\'codemirror.min.js\');
    }
    $exportVariables = \'
      twGetResourceContentType = "\'.$twGetResourceContentType.\'";
      fileManagerTopNavModalSkin = \'.$fileManagerTopNavModalSkin.\';
    \';
    $modx->regClientStartupHTMLBlock("<script>" . $exportVariables . $editorOutput . "</script>");
  }
}

//if user selects the option to activate this wrapper, we save him/her the trip of heading to System Settings - is this being too officious or intrusive?
if ($activateTinyMCE == 1) {
  if ($useEditor !== 1 || $whichEditor !== \'TinymceWrapper\') {
    $use = $modx->getObject(\'modSystemSetting\', \'use_editor\');
    $use->set(\'value\', 1);
    $use->save();
    $which = $modx->getObject(\'modSystemSetting\', \'which_editor\');
    $which->set(\'value\', \'TinymceWrapper\');
    $which->save();
  }
  //leave all elements alone - attack only resources
  if ($modxEventName == \'OnDocFormPrerender\') {
    //check if user wants to load TinyMCE for New Resources
      $loadTiny = 0; //default
    if($id && $resource->get(\'richtext\')) { //existing resource with RTE clicked
      $loadTiny = 1;
      }
    if($loadTiny == 0 && $newResources == 1 && $richtext_default == 1 && !$id) {
      $loadTiny = 1;
    }

    if ($loadTiny == 1) {
      //should we load jQuery?
      if ($jQuerySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
      }
      //should we load TinyMCE (from CDN or assets folder)?
      if ($tinySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
      }
      //let\'s init introtext, description and content textareas only if user has specified so in this plugin\'s properties
      if ($introtext == 1) {
        $introChunk = $modx->getChunk(\'TinymceWrapperIntrotext\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
        if ($introChunk) {
          $intro = $enableDisableTiny[0] . $introChunk;
        }
      }
      if ($description == 1) {
        $descChunk = $modx->getChunk(\'TinymceWrapperDescription\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
        if ($descChunk) {
          $desc = $enableDisableTiny[1] . $descChunk;
        }
      }
      if ($content == 1) {
        $conChunk = $modx->getChunk(\'TinymceWrapperContent\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
        if ($conChunk) {
          $con = $enableDisableTiny[2] . $conChunk;
        }
      }
      //all textareas depend on whether the Resource is Rich Text-enabled. We use so many IFs to protect against error
      //any and all Rich TVs + File and Image TVs will now be initiated
      //Now let\'s do the real init of all textareas
      //seems Ext.onReady is better than setTimeout, delay of 400
      $modx->regClientStartupHTMLBlock("<script>" . $enableDisableTinyClick . $browserFunctions . $autoSaveTextAreas . $browserTVs . "Ext.onReady(function () {" . $intro . $desc . $con . $richTv . "},this,{delay:".$addTinyMCEloadDelay."});</script>");
    }
    //let\'s see if the person wants TVs activated even when RTE is disabled for the Resource.
    elseif ($id && !$resource->get(\'richtext\')) {
        if ($tvAddict == 1) {
          if ($jQuerySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
          }
          if ($tinySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
          }
          $modx->regClientStartupHTMLBlock("<script>" . $enableDisableTinyClick . $browserFunctions . $autoSaveTextAreas . $browserTVs . "Ext.onReady(function () {" . $richTv . "},this,{delay:".$addTinyMCEloadDelay."});</script>");
        }
    }
  }
}
else{
  if ($modxEventName == \'OnDocFormPrerender\') {
        if ($tvSuperAddict == 1) {
          if ($jQuerySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
          }
          if ($tinySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
          }
          $modx->regClientStartupHTMLBlock("<script>" . $enableDisableTinyClick . $browserFunctions . $autoSaveTextAreas . $browserTVs . "Ext.onReady(function () {" . $richTv . "},this,{delay:".$addTinyMCEloadDelay."});</script>");
        }
  }
}

if ($modxEventName == \'OnManagerPageInit\' || $modxEventName == \'OnDocFormPrerender\') {
  $mediaPopUp =\'\';
  if ($fileManagerTopNavLink == 1 && $autoFileBrowser !== \'modxNativeBrowser\') {
    // inject file browser link to Manager Top Nav Media dropdown
    // if modxNativeBrowserTopNAVpresent is set to NO, remove native link in topNAV only when native browser is not in use
    $mediaPopUp = \'
      var fileManagerTopNavModal = "\'.$fileManagerTopNavModal.\'";
      fileManagerTopNavModalSkin = \'.$fileManagerTopNavModalSkin.\';
      Ext.onReady(function(){
        if (typeof tinyMCE !== "undefined" && fileManagerTopNavModal == "1") {
          $("body").append("<div id=\\\'tinyTopNAV\\\' style=\\\'display:none!important;border:0!important;outline:0!important;width:0;height:0;\\\'></div>");
          if(tinymce.editors.length < 1){
            tinymce.init({
              selector: "#tinyTopNAV",
              skin_url: \'.$fileManagerTopNavModalSkin.\',
              inline:true,
              forced_root_block : "",
              force_br_newlines : false,
              force_p_newlines : false
            })
          }
        }
      },this,{delay: 50})
      function mediaPopup(url){
        if ($("#tinyTopNAV").length) {
          tinyMCE.activeEditor.windowManager.open({
            title: "\'.$browserRTEtitle.\'",
            url: url,
            width : window.innerWidth / 1.2,
            height : window.innerHeight / 1.2
          },
          {
            oninsert: function(e) {
              e.preventDefault()
              return false;
          }
          })
        }
        else{
          var w = 880;
          var h = 570;
          var l = Math.floor((screen.width-w)/2);
          var t = Math.floor((screen.height-h)/2);
          var win = window.open(url, "", "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
        }
      }

      taskCounter = 0;
      var linkCheck = setInterval(function(){
        //requires no jQuery or TinyMCE - will work even if activateTinyMCE is false
        var fileBrowserBro = document.getElementById("file_browser");
        if(fileBrowserBro){
          var browserName = "\'.$autoFileBrowser.\'";
          var modxNativeBrowserTopNAVpresent = "\'.$modxNativeBrowserTopNAVpresent.\'";
          if(modxNativeBrowserTopNAVpresent !== "1" && browserName !== "modxNativeBrowser"){
            fileBrowserBro.style.display = "none";
          }
          taskCounter++;
          fileBrowserBro.insertAdjacentHTML( "beforebegin", "<li id=\\"tinymcewrapperTopNav\\"><a href=\\"javascript:;\\" onclick=\\"mediaPopup(\'.$browserTopNAVurl.\')\\">\'.$browserTopNAVtitle.\'<span class=\\"description\\">\'.$browserTopNAVsubtext.\'</span></a></li>");
        }
        if(taskCounter = 1)
          {clearInterval(linkCheck);
          }
      },1000);
    \';
     $modx->regClientStartupHTMLBlock("<script>" . $mediaPopUp . "</script>");
  }

  //let\'s catch only the textarea[content] when it is created. You can use livejquery or arrive.js if you like
  //make it non-obstrusive - mouseenter seems better than mouseout - works when modal pops and cursor is already on the textarea

  $quickUpdateCreate = $modx->getOption(\'quickUpdateCreate\', $sp);
  $quick = \'\';
  $quickChunk = $modx->getChunk(\'TinymceWrapperQuickUpdate\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));

  if ($quickChunk) {
    $quick = $quickChunk;
  }
  if ($quickUpdateCreate == 1){
    //do not load these twice when resources are being edited
    if ($modxEventName == \'OnManagerPageInit\') {
      if ($jQuerySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
      }
      if ($tinySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
      }
    }

    $quickUpdateTinyMCE = \'
      removeCodeMirror = 0;
      $(document).on("mouseenter", ".modx-window", function () {
        var tinyContent = $(this).find("textarea[name=content]");
        quickyId = "#"+tinyContent.attr("id");
        dataTiny = tinyContent.attr("id");
        // if ($(this).has("textarea[name=content]").length){//will catch Quick edit files from server
        if ($(this).has("input[name=published]").length && $(this).has("textarea[name=content]").length){
          if ($(this).has(".tinyEn").length){
          }
          else{
          // tinyContent.parent().parent().find("label").prepend("<button class=\\\'tinyEn x-form-field-wrap x-form-trigger\\\' onclick=enableTiny(quickyId,dataTiny)>Edit with TinyMCE?</button>&nbsp;&nbsp;&nbsp;");
          $(this).find(".x-toolbar-left-row").prepend("<p onclick=enableTiny(quickyId,dataTiny) class=\\\'x-btn x-btn-small x-btn-icon-small-left x-btn-noicon\\\' unselectable=\\\'on\\\'><em><button class=\\\'tinyEn x-btn-text\\\'>Edit with TinyMCE</button></em></p>");
          $(this).find(".tinyEn").attr("data-tiny",dataTiny);
          // $(this).find("button:contains(\\\'Close\\\')").first().attr("data-tiny","close-"+dataTiny);
          // $(this).find("button:contains(\\\'Save\\\')").first().attr("data-tiny","save-"+dataTiny);
          }
        }
      // })
      // .on("mouseout", tinymce.activeEditor, function () {
        // if(tinymce.editors.length > 1){}
        // if (tinyMCE.activeEditor !== null){
        //   if(tinyMCE.activeEditor.isHidden() != true){
        //     tinyMCE.activeEditor.save();
        //     javascript:console.log("saved");
        //   }
        // }
      });
      function enableTiny(quickyId,id){
        if($(quickyId).is(":visible")){
          enableTinyInit(quickyId);
          var id = dataTiny;
          $("button[data-tiny=\\\'"+id+"\\\']").html("Disable TinyMCE").parent().parent().attr("onclick","disTiny(dataTiny)");
        }
      }
      function disTiny(dataTiny){
        var dataTiny = dataTiny;
        tinymce.get(dataTiny).hide();
        $("button[data-tiny=\\\'"+dataTiny+"\\\']").html("Enable TinyMCE").parent().parent().attr("onclick","enTiny(dataTiny)");
        removeCodeMirror = 0;
        $(quickyId).parents(".modx-window").find(".CodeMirror, div.coder").remove();
        $(quickyId).parents(".modx-window").find(".ace_editor, div.coder").remove();
      }
      function enTiny(dataTiny){
        if($(quickyId).is(":visible")){
          $(quickyId).fadeIn().parents(".modx-window").find(".CodeMirror, div.coder").remove();
          $(quickyId).fadeIn().parents(".modx-window").find(".ace_editor, div.coder").remove();
          removeCodeMirror = 1;
          var dataTiny = dataTiny;
          tinymce.get(dataTiny).show();
          $("button[data-tiny=\\\'"+dataTiny+"\\\']").html("Disable TinyMCE").parent().parent().attr("onclick","disTiny(dataTiny)");
        }
      }
      function enableTinyInit(quickyId){
        $(quickyId).fadeIn().parents(".modx-window").find(".CodeMirror, div.coder").remove();
        $(quickyId).fadeIn().parents(".modx-window").find(".ace_editor, div.coder").remove();
        removeCodeMirror = 1;
        $(quickyId).parents(".modx-window").find(".x-tab-panel-body.x-tab-panel-body-top").css({"overflow":"hidden","overflow-y":"auto"});
        \' .$quick. \'
      }
      \';
    $modx->regClientStartupHTMLBlock("<script>" . $browserFunctions . $quickUpdateTinyMCE . "</script>");
  }
}',
    ),
  ),
  'f81ef9f6b892223ce3d240d0ece39b79' => 
  array (
    'criteria' => 
    array (
      'templatename' => 'tw_ImogenTheme',
    ),
    'object' => 
    array (
      'id' => 3,
      'source' => 0,
      'property_preprocess' => 0,
      'templatename' => 'tw_ImogenTheme',
      'description' => 'Template for backend demo, and awesome frontend magic editing. Each [[!TinyMagicPublisher]] call has the data-tiny attribute to each tag .. data-tiny="np-pagetitle"  ... and then [[*pagetitle:TinyMagicPublisherModifier]]... to use Markdown [[*content:Tiny',
      'editor_type' => 0,
      'category' => 1,
      'icon' => '',
      'template_type' => 0,
      'content' => '<!DOCTYPE html>
<html lang="en">

<head>

    <!-- <meta charset="utf-8"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>[[*pagetitle]]</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/css/bootstrap.min.css" type="text/css">

    <!-- Custom Fonts -->
    <link href=\'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800\' rel=\'stylesheet\' type=\'text/css\'>
    <link href=\'http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic\' rel=\'stylesheet\' type=\'text/css\'>
    <link rel="stylesheet" href="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/font-awesome/css/font-awesome.min.css" type="text/css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/css/animate.min.css" type="text/css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/css/creative.css" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.5/jquery.bxslider.css" rel="stylesheet" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .twGalleryJson{
            width:100%;
        }
        .mce-text {
            color: #333!important;
        }
        .twGalleryJson .bx-wrapper{
            max-width: 500px!important
        }
        [data-tiny=np-content] a{
            color:#0F5C92;
        }
        input{
            color:black !important;
        }
    </style>
</head>

<body id="page-top">

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">Start Bootstrap</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="page-scroll" href="#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services">Services</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#portfolio">Portfolio</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <header>
        <div class="header-content">
            <div class="header-content-inner">
                <h1 data-tiny="np-pagetitle">[[*pagetitle:TinyMagicPublisherModifier]]</h1>
                <hr>
                <p data-tiny="np-introtext">[[*introtext:TinyMagicPublisherModifier]]</p>
                <a href="#about" class="btn btn-primary btn-xl page-scroll">Find Out More</a>
            </div>
        </div>
    </header>

    <section class="bg-primary" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center-remove" data-tiny="np-content">[[*content:TinyMagicPublisherModifier]]</div>
            </div>
        </div>
    </section>

    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">At Your Service</h2>
                    <hr class="primary">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-diamond wow bounceIn text-primary"></i>
                        <div  data-tiny="np-TinymceWrapperServiceTV1">[[*TinymceWrapperServiceTV1:TinyMagicPublisherModifier]]</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-paper-plane wow bounceIn text-primary" data-wow-delay=".1s"></i>
                        <div  data-tiny="np-TinymceWrapperServiceTV2">[[*TinymceWrapperServiceTV2:TinyMagicPublisherModifier]]</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-newspaper-o wow bounceIn text-primary" data-wow-delay=".2s"></i>
                        <div  data-tiny="np-TinymceWrapperServiceTV3">[[*TinymceWrapperServiceTV3:TinyMagicPublisherModifier]]</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-heart wow bounceIn text-primary" data-wow-delay=".3s"></i>
                        <div  data-tiny="np-TinymceWrapperServiceTV4">[[*TinymceWrapperServiceTV4:TinyMagicPublisherModifier]]</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="no-padding" id="portfolio">
        <div class="container-fluid">
            <div class="row no-gutter">
                <div class="col-lg-4 col-sm-6">
                    <a href="#" class="portfolio-box">
                        <img src="[[++base_url]][[*TinymceWrapperImageTV1]]" class="img-responsive" data-tiny-image="np-TinymceWrapperImageTV1" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Category
                                </div>
                                <div class="project-name" data-tiny="np-TinymceWrapperCategoryTV1">
                                    [[*TinymceWrapperCategoryTV1:TinyMagicPublisherModifier]]
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="#" class="portfolio-box">
                        <img src="[[++base_url]][[*TinymceWrapperImageTV2]]" class="img-responsive" data-tiny-image="np-TinymceWrapperImageTV2" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Category
                                </div>
                                <div class="project-name" data-tiny="np-TinymceWrapperCategoryTV2">
                                    [[*TinymceWrapperCategoryTV2:TinyMagicPublisherModifier]]
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="#" class="portfolio-box">
                        <img src="[[++base_url]][[*TinymceWrapperImageTV3]]" class="img-responsive" data-tiny-image="np-TinymceWrapperImageTV3" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Category
                                </div>
                                <div class="project-name" data-tiny="np-TinymceWrapperCategoryTV3">[[*TinymceWrapperCategoryTV3:TinyMagicPublisherModifier]]</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="#" class="portfolio-box">
                        <img src="[[++base_url]][[*TinymceWrapperImageTV4]]" class="img-responsive" data-tiny-image="np-TinymceWrapperImageTV4" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Category
                                </div>
                                <div class="project-name" data-tiny="np-TinymceWrapperCategoryTV4">[[*TinymceWrapperCategoryTV4:TinyMagicPublisherModifier]]</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="#" class="portfolio-box">
                        <img src="[[++base_url]][[*TinymceWrapperImageTV5]]" class="img-responsive" data-tiny-image="np-TinymceWrapperImageTV5" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Category
                                </div>
                                <div class="project-name" data-tiny="np-TinymceWrapperCategoryTV5">[[*TinymceWrapperCategoryTV5:TinyMagicPublisherModifier]]</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="#" class="portfolio-box">
                        <img src="[[++base_url]][[*TinymceWrapperImageTV6]]" class="img-responsive" data-tiny-image="np-TinymceWrapperImageTV6" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Category
                                </div>
                                <div class="project-name" data-tiny="np-TinymceWrapperCategoryTV6">[[*TinymceWrapperCategoryTV6:TinyMagicPublisherModifier]]</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <aside class="bg-dark">
        <div class="container text-center">
            <div class="call-to-action" data-tiny="np-TinymceWrapperMiscTV1">[[*TinymceWrapperMiscTV1:TinyMagicPublisherModifier]]</div>
        </div>
    </aside>

    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center" data-tiny="np-TinymceWrapperMiscTV2">[[*TinymceWrapperMiscTV2:TinyMagicPublisherModifier]]</div>
                <div class="col-lg-4 col-lg-offset-2 text-center">
                    <i class="fa fa-phone fa-3x wow bounceIn" style="visibility: visible; animation-name: bounceIn;"></i>
                    <p>123-456-6789</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fa fa-envelope-o fa-3x wow bounceIn" data-wow-delay=".1s" style="visibility: visible; animation-delay: 0.1s; animation-name: bounceIn;"></i>
                    <p><a href="mailto:your-email@your-domain.com">feedback@startbootstrap.com</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- jQuery -->
    <script src="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/js/jquery.easing.min.js"></script>
    <script src="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/js/jquery.fittext.js"></script>
    <script src="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/js/wow.min.js"></script>
    <script src="[[++assets_url]]components/tinymcewrapper/frontend/imogen_theme/js/creative.js"></script>
    <!-- Custom Theme JavaScript -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.5/jquery.bxslider.min.js"></script>
    <script>
        $(\'.bxslider\').bxSlider({
          mode: \'fade\',
          captions: true
        });
    </script>
[[!TinyMagicPublisher?
&npPureMagicNonTraditional=`1`
&npTraditionalTinyChunk=`TinymceWrapperNPtraditional`
&npDebug=`0`
&autoFileBrowser=`elFinderBrowser`
&elFinderBrowserRTEurl=`[[~[[TinymceWrapperGRF? &kF=pagetitle &kFv=tw_elfinder_browser &gNuFv=id]]? &scheme=full &rememberLastDir=1 &defaultView=icons &unlocked=1 &theme=`windows10`]]`
&responsivefilemanagerBrowserRTEurl=`[[++assets_url]]components/tinymcewrapper/responsivefilemanager/filemanager/dialog.php?type=1`
&responsivefilemanagerBrowserRTEtitle=`Responsive FileManager`
&roxyFilemanBrowserRTEtitle=`Roxy Fileman`
&roxyFilemanBrowserRTEurl=`[[++assets_url]]components/tinymcewrapper/roxy/fileman/roxy.php`
&mainCSSfile=`[[++assets_url]]components/tinymcewrapper/frontend/mainCSSfile.css`
&jsTopBarButtonsTpl=`TinymceWrapperNPpublishButtonsTpl`
&npCreateResource =`[[!TinymceWrapperGRF? &kF=pagetitle &kFv=tw_magic_create_page &gNuFv=id]]`
&npNoShow=``
&npCancelCreateId=`[[!TinymceWrapperGRF? &kF=pagetitle &kFv=TinymceWrapper &gNuFv=id]]`
&chunkSuffix=``
&editThisButton=`Edit`
&createNewButton=`New`
&tinymceCDN=`//cdn.tinymce.com/4/tinymce.min.js`
&jQueryCDN=``
&npNoShowChildOfParents=``
&cacheable=`0`
&show=`pagetitle,alias,introtext,content,richtext,TinymceWrapperTags,TinymceWrapperServiceTV1,TinymceWrapperServiceTV2,TinymceWrapperServiceTV3,TinymceWrapperServiceTV4,TinymceWrapperCategoryTV1,TinymceWrapperCategoryTV2,TinymceWrapperCategoryTV3,TinymceWrapperCategoryTV4,TinymceWrapperCategoryTV5,TinymceWrapperCategoryTV6,TinymceWrapperImageTV1,TinymceWrapperImageTV2,TinymceWrapperImageTV3,TinymceWrapperImageTV4,TinymceWrapperImageTV5,TinymceWrapperImageTV6,TinymceWrapperMiscTV1,TinymceWrapperMiscTV2,hidemenu,published,publishedby,description,menuindex,menutitle,longtitle,parent,template,deleted,TinyJSONGalleryTV`
&twTagsTV = `TinymceWrapperTags`
&twExistingTagsChunk = `twExistingTags`
&commonTinyMCECodeChunk = `TinymceWrapperNPCommonCode`
&required=`pagetitle,content`
&published = `1`
&parentid=`[[!TinymceWrapperGRF? &kF=pagetitle &kFv=TinymceWrapper &gNuFv=id]]`
&parents = ``
&templates = ``
&templateid=`tw_ImogenTheme`
&initdatepicker=`1`
&cssfile= ``
&filetpl=`twBROWSERnpFileTpl`
&imagetpl=`twBROWSERnpImageTpl`
&modxGalleryAssetsUrl =`[[++assets_url]]`
&TinyJSONGalleryJS = `[[++assets_url]]components/tinymcewrapper/gallery/js/TinyJSONGallery.js`
&galleryBackUpRTEskin = `[[++assets_url]]/components/tinymcewrapper/tinymceskins/modxPericles`
]]

<script>
if($("#np-TinyJSONGalleryTV").length){
  $("<a style=display:block;text-align:center;width:100%;top:28px;text-decoration:none;font-size:20px;margin-bottom:48px; href=javascript:; onclick=\\"popGal(\'np-TinyJSONGalleryTV\', \'My Nature Gallery\', \'\', \'\', 1)\\">CLICK TO OPEN GALLERY MANAGER</a>").insertBefore("h1[data-tiny=np-pagetitle]").parent();
}
$("[data-tiny]").parents("a").on("click",function(e){e.preventDefault;return false;})
</script>
</body>

</html>',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
    ),
  ),
  '1c9911fc5fc10baeaf72129c6e101f11' => 
  array (
    'criteria' => 
    array (
      'templatename' => 'tw_Empty',
    ),
    'object' => 
    array (
      'id' => 4,
      'source' => 0,
      'property_preprocess' => 0,
      'templatename' => 'tw_Empty',
      'description' => 'Empty template to solve the confusion of user\'s MODX setup changing the "empty" template of elFinder and tw_traditional_create_page resources',
      'editor_type' => 0,
      'category' => 1,
      'icon' => '',
      'template_type' => 0,
      'content' => '[[*content]]',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
    ),
  ),
  'e48d285b491f4ab4b14aef50990025a2' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperMiscTV1',
    ),
    'object' => 
    array (
      'id' => 1,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperMiscTV1',
      'caption' => 'TinymceWrapperMiscTV1',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 1,
      'display' => 'default',
      'default_text' => '<h2>Free Download at Start Bootstrap!</h2>
<a href="#" class="btn btn-default btn-xl wow tada">Download Now!</a>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<h2>Free Download at Start Bootstrap!</h2>
<a href="#" class="btn btn-default btn-xl wow tada">Download Now!</a>',
    ),
  ),
  '39e62b2683f45557c792895166063ad0' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperMiscTV2',
    ),
    'object' => 
    array (
      'id' => 2,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperMiscTV2',
      'caption' => 'TinymceWrapperMiscTV2',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 1,
      'display' => 'default',
      'default_text' => '<h2 class="section-heading">Let\'s Get In Touch!</h2>
<hr class="primary">
<p>Ready to start your next project with us? That\'s great! Give us a call or send us an email and we will get back to you as soon as possible!</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<h2 class="section-heading">Let\'s Get In Touch!</h2>
<hr class="primary">
<p>Ready to start your next project with us? That\'s great! Give us a call or send us an email and we will get back to you as soon as possible!</p>',
    ),
  ),
  '4e5370eabfeac461dd3d49cbfa44ca44' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperServiceTV1',
    ),
    'object' => 
    array (
      'id' => 3,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperServiceTV1',
      'caption' => 'TinymceWrapperServiceTV1',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<h3>Sturdy Templates</h3>
<p class="text-muted">Our templates are updated regularly so they don\'t break.</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<h3>Sturdy Templates</h3>
<p class="text-muted">Our templates are updated regularly so they don\'t break.</p>',
    ),
  ),
  '9918755714f324ce1d8da9f2414a340f' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperServiceTV2',
    ),
    'object' => 
    array (
      'id' => 4,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperServiceTV2',
      'caption' => 'TinymceWrapperServiceTV2',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<h3>Ready to Ship</h3>
<p class="text-muted">You can use this theme as is, or you can make changes!</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<h3>Ready to Ship</h3>
<p class="text-muted">You can use this theme as is, or you can make changes!</p>',
    ),
  ),
  '1033580e815912f472ec62ccd1cb0881' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperServiceTV3',
    ),
    'object' => 
    array (
      'id' => 5,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperServiceTV3',
      'caption' => 'TinymceWrapperServiceTV3',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<h3>Up to Date</h3>
<p class="text-muted">We update dependencies to keep things fresh.</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<h3>Up to Date</h3>
<p class="text-muted">We update dependencies to keep things fresh.</p>',
    ),
  ),
  '2d3c42c33e92e69d89b9465a35372bc7' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperServiceTV4',
    ),
    'object' => 
    array (
      'id' => 6,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperServiceTV4',
      'caption' => 'TinymceWrapperServiceTV4',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<h3>Made with Love</h3>
<p class="text-muted">You have to make your websites with love these days!</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<h3>Made with Love</h3>
<p class="text-muted">You have to make your websites with love these days!</p>',
    ),
  ),
  'd34e567edc1a6413fc95d2fed3fca19c' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperCategoryTV1',
    ),
    'object' => 
    array (
      'id' => 7,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperCategoryTV1',
      'caption' => 'TinymceWrapperCategoryTV1',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<p>Hey, you hovered huh! now click to see my toolbar</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<p>Hey, you hovered huh! now click to see my toolbar</p>',
    ),
  ),
  'c90c6295364e57cd47176031ead01c49' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperCategoryTV2',
    ),
    'object' => 
    array (
      'id' => 8,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperCategoryTV2',
      'caption' => 'TinymceWrapperCategoryTV2',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<p>You can change my background image, cool huh!?</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<p>You can change my background image, cool huh!?</p>',
    ),
  ),
  'e01df39edb5507f79fe8d6e2e28aa388' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperCategoryTV3',
    ),
    'object' => 
    array (
      'id' => 9,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperCategoryTV3',
      'caption' => 'TinymceWrapperCategoryTV3',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<p>Ace & CodeMirror work just fine</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<p>Ace & CodeMirror work just fine</p>',
    ),
  ),
  '5db0595f326cb8e7be6777fc38386215' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperCategoryTV4',
    ),
    'object' => 
    array (
      'id' => 10,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperCategoryTV4',
      'caption' => 'TinymceWrapperCategoryTV4',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<p>Project Name</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<p>Project Name</p>',
    ),
  ),
  '3013d94d39f55512ef668b39e7e3961d' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperCategoryTV5',
    ),
    'object' => 
    array (
      'id' => 11,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperCategoryTV5',
      'caption' => 'TinymceWrapperCategoryTV5',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<p>Project Name</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<p>Project Name</p>',
    ),
  ),
  '7614750e1f24a272e163d9dc7936d7e4' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperCategoryTV6',
    ),
    'object' => 
    array (
      'id' => 12,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'richtext',
      'name' => 'TinymceWrapperCategoryTV6',
      'caption' => 'TinymceWrapperCategoryTV6',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '<p>Project Name</p>',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<p>Project Name</p>',
    ),
  ),
  'c28f2486f7ec18a1fcefe10ec65444d6' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperImageTV1',
    ),
    'object' => 
    array (
      'id' => 13,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'image',
      'name' => 'TinymceWrapperImageTV1',
      'caption' => 'TinymceWrapperImageTV1',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/1.jpg',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/1.jpg',
    ),
  ),
  '0dfe0999307991eb4ec2cc23c78605b1' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperImageTV2',
    ),
    'object' => 
    array (
      'id' => 14,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'image',
      'name' => 'TinymceWrapperImageTV2',
      'caption' => 'TinymceWrapperImageTV2',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/2.jpg',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/2.jpg',
    ),
  ),
  '1ce5379a7acd7dc7c7f8f99677ccfa4a' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperImageTV3',
    ),
    'object' => 
    array (
      'id' => 15,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'image',
      'name' => 'TinymceWrapperImageTV3',
      'caption' => 'TinymceWrapperImageTV3',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/3.jpg',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/3.jpg',
    ),
  ),
  'cafecf318aa94a74de7e82517ce3a55f' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperImageTV4',
    ),
    'object' => 
    array (
      'id' => 16,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'image',
      'name' => 'TinymceWrapperImageTV4',
      'caption' => 'TinymceWrapperImageTV4',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/4.jpg',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/4.jpg',
    ),
  ),
  'ec2e861e19f28e8b3fec2e62429b2fe8' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperImageTV5',
    ),
    'object' => 
    array (
      'id' => 17,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'image',
      'name' => 'TinymceWrapperImageTV5',
      'caption' => 'TinymceWrapperImageTV5',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/5.jpg',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/5.jpg',
    ),
  ),
  'eaa9ea03d70f51fd03d5fcf91bf929fa' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperImageTV6',
    ),
    'object' => 
    array (
      'id' => 18,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'image',
      'name' => 'TinymceWrapperImageTV6',
      'caption' => 'TinymceWrapperImageTV6',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/6.jpg',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'assets/components/tinymcewrapper/frontend/imogen_theme/img/portfolio/6.jpg',
    ),
  ),
  '0c9483720c5b79a6794e82c830d6d313' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperTags',
    ),
    'object' => 
    array (
      'id' => 19,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'text',
      'name' => 'TinymceWrapperTags',
      'caption' => 'TinymceWrapperTags',
      'description' => 'Supply the name of this TV in TnyMagicPublisher &show and &twTagsTV. Comma-delimetered list of spaceless tags is best',
      'editor_type' => 0,
      'category' => 1,
      'locked' => 0,
      'elements' => '',
      'rank' => 0,
      'display' => 'default',
      'default_text' => '',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:1:{s:10:"allowBlank";s:4:"true";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '',
    ),
  ),
  '6819f0aea0d96751dbd9659dd7136620' => 
  array (
    'criteria' => 
    array (
      'category' => 'TinyCustomBrowsers',
    ),
    'object' => 
    array (
      'id' => 2,
      'parent' => 1,
      'category' => 'TinyCustomBrowsers',
      'rank' => 0,
    ),
  ),
  '51b15f21bad6933931de5dd238d5d7ab' => 
  array (
    'criteria' => 
    array (
      'category' => 'elFinderVolumes',
    ),
    'object' => 
    array (
      'id' => 3,
      'parent' => 2,
      'category' => 'elFinderVolumes',
      'rank' => 0,
    ),
  ),
  '62ca2a7e8130cf567e35cc23dc4299d0' => 
  array (
    'criteria' => 
    array (
      'name' => 'elfinderLocalFileSystem1',
    ),
    'object' => 
    array (
      'id' => 1,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'elfinderLocalFileSystem1',
      'description' => 'Template for volume1. Pure JSON format, no comments allowed. You may add other features from https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1',
      'editor_type' => 0,
      'category' => 3,
      'cache_type' => 0,
      'snippet' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
    ),
  ),
  '92ccc6182fe24a9f8af6d6cb154cfe50' => 
  array (
    'criteria' => 
    array (
      'name' => 'elfinderLocalFileSystem2',
    ),
    'object' => 
    array (
      'id' => 2,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'elfinderLocalFileSystem2',
      'description' => 'Template for volume2. Pure JSON format, no comments allowed. You may add other features from https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1',
      'editor_type' => 0,
      'category' => 3,
      'cache_type' => 0,
      'snippet' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
    ),
  ),
  '434875e16fd4285fd5ab5723c13b9ec2' => 
  array (
    'criteria' => 
    array (
      'name' => 'elfinderLocalFileSystem3',
    ),
    'object' => 
    array (
      'id' => 3,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'elfinderLocalFileSystem3',
      'description' => 'Template for volume3. Pure JSON format, no comments allowed. You may add other features from https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1',
      'editor_type' => 0,
      'category' => 3,
      'cache_type' => 0,
      'snippet' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
    ),
  ),
  '35d86030e63f3a9104fd710f9158a797' => 
  array (
    'criteria' => 
    array (
      'name' => 'elfinderLocalFileSystem4',
    ),
    'object' => 
    array (
      'id' => 4,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'elfinderLocalFileSystem4',
      'description' => 'Template for volume4. Pure JSON format, no comments allowed. You may add other features from https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1',
      'editor_type' => 0,
      'category' => 3,
      'cache_type' => 0,
      'snippet' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
    ),
  ),
  'd9664f56c324ebee2d06db4a73cf5050' => 
  array (
    'criteria' => 
    array (
      'name' => 'elfinderLocalFileSystemPersonal',
    ),
    'object' => 
    array (
      'id' => 5,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'elfinderLocalFileSystemPersonal',
      'description' => 'Template for Personal Folder. Pure JSON format, no comments allowed. You may add other features from https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1',
      'editor_type' => 0,
      'category' => 3,
      'cache_type' => 0,
      'snippet' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
    ),
  ),
  'bc6fe7b2a62845edc6a77fc5fb2962e2' => 
  array (
    'criteria' => 
    array (
      'name' => 'elfinderLocalFileSystem5',
    ),
    'object' => 
    array (
      'id' => 6,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'elfinderLocalFileSystem5',
      'description' => 'Template for volume5. Pure JSON format, no comments allowed. You may add other features from https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1',
      'editor_type' => 0,
      'category' => 3,
      'cache_type' => 0,
      'snippet' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '{
  "id": "[[+id]]",
  "driver": "LocalFileSystem",
  "alias": "[[+alias]]",
  "path": "[[+path]]",
  "URL": "[[+url]]",
  "tmbPath": "[[+tmbPath]]",
  "quarantine": "[[+quarantine]]",
  "disabled": [[+disabled]],
  "startPath": "[[+startPath]]",
  "accessControl": "[[+accessControl]]",
  "attributes": [[+attributes]]
}',
    ),
  ),
  '3aa8e8b2626679a8292eaa425ca4270b' => 
  array (
    'criteria' => 
    array (
      'name' => 'elfinderConnector',
    ),
    'object' => 
    array (
      'id' => 1,
      'source' => 0,
      'property_preprocess' => 1,
      'name' => 'elfinderConnector',
      'description' => 'Web file manager Version: 2.1.6, protocol version: 2.0 jQuery/jQuery UI: 1.8.0/1.8.23
This snippet is called by assets/components/tinymcewrapper/browserConnectors/elfinder.php',
      'editor_type' => 0,
      'category' => 3,
      'cache_type' => 0,
      'snippet' => '/*TinymceWrapper elfinderConnector is called by assets/components/tinymcewrapper/browserConnectors/elfinder.php
To use a MODx resource instead of this external file,
simply create your own resource, with empty template and call [[!elfinderConnector@myPropertySet]],
insert the new resource\'s url in your elFinder.html init, thus replacing the old one.

-------------------Roadmap:
-Add dropbox and AWS
-Add Media Source for File/Image TVs
-Look for more ways to be awesome;
---------------------------

Use freely, recode freely, report freely, enjoy freely
Dedicated to those who have cried
---------------------------

http://www.leofec.com/modx-revolution/
-donshakespeare in the MODx forum
*/
error_reporting(0); // Set E_ALL for debuging

include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderConnector.class.php\';
include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinder.class.php\';
include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderVolumeDriver.class.php\';
include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderVolumeLocalFileSystem.class.php\';
// Required for MySQL storage connector
// include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderVolumeMySQL.class.php\';
// Required for FTP connector support
include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderVolumeFTP.class.php\';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  \'.\' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/

$sp = $scriptProperties;
$getParam = \'\';
$getLocation = getMyParams(\'hide\');

$user = $modx->getObject(\'modUser\', array(\'id\' => $modx->user->get(\'id\'))); // get present user infos

$suffix = $modx->getOption(\'chunkSuffix\', $sp);
$enablePersonalFolder = $modx->getOption(\'enablePersonalFolder\', $sp);
$sudoAdmin = $modx->getOption(\'sudoAdmin\', $sp);
$sudoUserGroup = $modx->getOption(\'sudoUserGroup\', $sp);

$sudoAdminIDs = listArray($modx->getOption(\'sudoAdminIDs\', $sp, array()));

$twAutoCreatePersonalFolders = $modx->getOption(\'twAutoCreatePersonalFolders\', $sp);

$uSGr1 = $modx->getOption(\'location1AccessUserGroup\', $sp);
$uSGr2 = $modx->getOption(\'location2AccessUserGroup\', $sp);
$uSGr3 = $modx->getOption(\'location3AccessUserGroup\', $sp);
$uSGr4 = $modx->getOption(\'location4AccessUserGroup\', $sp);
$uSGr5 = $modx->getOption(\'location5AccessUserGroup\', $sp);
$uSGrPersonal = $modx->getOption(\'locationPersonalAccessUserGroup\', $sp);

if(!$user->isMember(listArray($sudoUserGroup.\',\'.$uSGr1.\',\'.$uSGr2.\',\'.$uSGr3.\',\'.$uSGr4.\',\'.$uSGrPersonal))) die;

$location1 = $modx->getOption(\'location1\', $sp);
$location2 = $modx->getOption(\'location2\', $sp);
$location3 = $modx->getOption(\'location3\', $sp);
$location4 = $modx->getOption(\'location4\', $sp);
$location5 = $modx->getOption(\'location5\', $sp);
$locationPersonal = $modx->getOption(\'locationPersonal\', $sp);

$location1Path = $modx->getOption(\'location1Path\', $sp);
$location2Path = $modx->getOption(\'location2Path\', $sp);
$location3Path = $modx->getOption(\'location3Path\', $sp);
$location4Path = $modx->getOption(\'location4Path\', $sp);
$location5Path = $modx->getOption(\'location5Path\', $sp);
$locationPersonalPath = $modx->getOption(\'locationPersonalPath\', $sp);

$location1Url = $modx->getOption(\'location1Url\', $sp);
$location2Url = $modx->getOption(\'location2Url\', $sp);
$location3Url = $modx->getOption(\'location3Url\', $sp);
$location4Url = $modx->getOption(\'location4Url\', $sp);
$location5Url = $modx->getOption(\'location5Url\', $sp);
$locationPersonalUrl = $modx->getOption(\'locationPersonalUrl\', $sp);

$location1tmbPath = $modx->getOption(\'location1tmbPath\', $sp);
$location2tmbPath = $modx->getOption(\'location2tmbPath\', $sp);
$location3tmbPath = $modx->getOption(\'location3tmbPath\', $sp);
$location4tmbPath = $modx->getOption(\'location4tmbPath\', $sp);
$location5tmbPath = $modx->getOption(\'location5tmbPath\', $sp);
$locationPersonaltmbPath = $modx->getOption(\'locationPersonaltmbPath\', $sp);

$location1Quarantine = $modx->getOption(\'location1Quarantine\', $sp);
$location2Quarantine = $modx->getOption(\'location2Quarantine\', $sp);
$location3Quarantine = $modx->getOption(\'location3Quarantine\', $sp);
$location4Quarantine = $modx->getOption(\'location4Quarantine\', $sp);
$location5Quarantine = $modx->getOption(\'location5Quarantine\', $sp);
$locationPersonalQuarantine = $modx->getOption(\'locationPersonalQuarantine\', $sp);

$location1Disabled = $modx->getOption(\'location1Disabled\', $sp);
$location2Disabled = $modx->getOption(\'location2Disabled\', $sp);
$location3Disabled = $modx->getOption(\'location3Disabled\', $sp);
$location4Disabled = $modx->getOption(\'location4Disabled\', $sp);
$location5Disabled = $modx->getOption(\'location5Disabled\', $sp);
$locationPersonalDisabled = $modx->getOption(\'locationPersonalDisabled\', $sp);

$usPer1 = $modx->getOption(\'location1AccessPermission\', $sp);
$usPer2 = $modx->getOption(\'location2AccessPermission\', $sp);
$usPer3 = $modx->getOption(\'location3AccessPermission\', $sp);
$usPer4 = $modx->getOption(\'location4AccessPermission\', $sp);
$usPer5 = $modx->getOption(\'location5AccessPermission\', $sp);
$usPerPersonal = $modx->getOption(\'locationPersonalAccessPermission\', $sp);

function getMyParams($name){
  if (isset($_GET[$name]) && !empty($_GET[$name])) {
    $getParam = filter_var($_GET[$name], FILTER_SANITIZE_STRING);
    $getParam = rawurldecode($getParam);
    return $getParam;
  }
 }

function forMembers($attr, $path, $data, $volume) {
    if (strpos(basename($path), \'.\') === 0){ //hide .tmb folders
      return (!($attr == \'read\' || $attr == \'write\'));
    }
    //example of what you can do
   /* if (strpos(basename($path), \'core\') === 0 || strpos(basename($path), \'manager\') === 0){
      return ($attr !== \'read\' && $attr == \'locked\');
    }*/
    // if($onlyAdministratorMembers == 1){
    //     if (strpos(dirname($path), \'/\') !== false){
    //       return !(($attr == \'read\' || $attr == \'write\'));
    //       // return ($attr == \'read\' || $attr == \'locked\' || $attr !== \'write\');
    //     }
    // }
}

function lockedButVisible($attr, $path, $data, $volume) {
    if (strpos(basename($path), \'.\') === 0){ //hide .tmb folders
      return (!($attr == \'read\' || $attr == \'write\'));
    }
    if (strpos(dirname($path), \'/\') !== false){
         return ($attr == \'read\' || $attr == \'locked\');
    }
}

function forAdmin($attr, $path, $data, $volume) {
    if (strpos(basename($path), \'.\') === 0){ //hide all .tmb folders
      return (!($attr == \'read\' || $attr == \'write\'));
    }
}

function hideThis($attr, $path, $data, $volume) {
    if (strpos(dirname($path), \'/\') !== false){
         return (!($attr == \'read\' || $attr == \'write\'));
    }
}

function listArray($thisList){
    $thisList = preg_replace(\'/\\s+/\', \'\', $thisList);
    $thisList = preg_replace("/[^a-z0-9,-_]+/i", \' \', $thisList);
    $thisList = explode(\',\', $thisList);
    return $thisList;
}

function jDecode($item){
    $json = $item;
    $json = json_decode($json, true);
    return $json;
}

if($sudoAdmin && $user->isMember(listArray($sudoUserGroup))) {
    $usPer1 = $usPer2 = $usPer3 = $usPer4 = $usPerPersonal = \'[]\';
}

$loc1 = $loc2 = $loc3 = $loc4 = $loc5 = $locPersonal =  array();


// $myModxPermission = \'forMembers\';
$myModxPermission = $modx->getOption(\'myModxPermission\', $sp);

// if($user->isMember(listArray($sudoUserGroup)) && getMyParams(\'unlocked\') == 1) {
if(in_array($modx->user->get(\'id\'), $sudoAdminIDs) && getMyParams(\'unlocked\') == 1) {
    $myModxPermission =\'forAdmin\';
}

$myModxPermission1 = $myModxPermission2 = $myModxPermission3 = $myModxPermission4 = $myModxPermission5 = $myModxPermissionP = $myModxPermission;

$startPath1 = $modx->getOption(\'location1Path\', $sp).getMyParams(\'folder\');
$startPath2 = $modx->getOption(\'location2Path\', $sp).getMyParams(\'folder\');
$startPath3 = $modx->getOption(\'location3Path\', $sp).getMyParams(\'folder\');
$startPath4 = $modx->getOption(\'location4Path\', $sp).getMyParams(\'folder\');
$startPath5 = $modx->getOption(\'location5Path\', $sp).getMyParams(\'folder\');
$startPathPersonal = $modx->getOption(\'locationPersonalPath\', $sp).getMyParams(\'folder\');

//add as many fldrs/locations as you like -- see docs for more wonderful options
//check if browsing user can indeed access this location
//check if this location has a name or it is disabled by being rendered nameless
if($user->isMember(listArray($uSGr1)) && $location1) {
    if (strpos($getLocation, \'1\') !== false){
        $myModxPermission1 = \'hideThis\';
}
    $loc1Properties = array(
        \'id\'            =>\'loc1\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location1,
        \'path\'          => $location1Path,
        \'url\'           => $location1Url,
        \'tmbPath\'       => $location1tmbPath,
        \'quarantine\'    => $location1Quarantine,
        \'disabled\'      => $location1Disabled ?:\'[]\',
        \'startPath\'     => $startPath1,
        \'accessControl\' => $myModxPermission1,
        \'attributes\'    => $usPer1 ?: \'[]\'
      );
    $loc1 = $modx->getChunk(\'elfinderLocalFileSystem1\'.$suffix , $loc1Properties);
    if($loc1){
        $loc1 = jDecode($loc1);
    }
}

if($user->isMember(listArray($uSGr2)) && $location2) {
    if (strpos($getLocation, \'2\') !== false){
        $myModxPermission2 = \'hideThis\';
    }
    $loc2Properties = array(
        \'id\'            =>\'loc2\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location2,
        \'path\'          => $location2Path,
        \'url\'           => $location2Url,
        \'tmbPath\'       => $location2tmbPath,
        \'quarantine\'    => $location2Quarantine,
        \'disabled\'      => $location2Disabled ?:\'[]\',
        \'startPath\'     => $startPath2,
        \'accessControl\' => $myModxPermission2,
        \'attributes\'    => $usPer2 ?: \'[]\'
      );
    $loc2 = $modx->getChunk(\'elfinderLocalFileSystem2\'.$suffix , $loc2Properties);
    if($loc2){
        $loc2 = jDecode($loc2);
    }
}

if($user->isMember(listArray($uSGr3)) && $location3) {
    if (strpos($getLocation, \'3\') !== false){
        $myModxPermission3 = \'hideThis\';
    }
    $loc3Properties = array(
        \'id\'            =>\'loc3\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location3,
        \'path\'          => $location3Path,
        \'url\'           => $location3Url,
        \'tmbPath\'       => $location3tmbPath,
        \'quarantine\'    => $location3Quarantine,
        \'disabled\'      => $location3Disabled ?:\'[]\',
        \'startPath\'     => $startPath3,
        \'accessControl\' => $myModxPermission3,
        \'attributes\'    => $usPer3 ?: \'[]\'
      );
    $loc3 = $modx->getChunk(\'elfinderLocalFileSystem3\'.$suffix , $loc3Properties);
    if($loc3){
        $loc3 = jDecode($loc3);
    }
}

if($user->isMember(listArray($uSGr4)) && $location4) {
    if (strpos($getLocation, \'4\') !== false){
        $myModxPermission4 = \'hideThis\';
    }
    $loc4Properties = array(
        \'id\'            =>\'loc4\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location4,
        \'path\'          => $location4Path,
        \'url\'           => $location4Url,
        \'tmbPath\'       => $location4tmbPath,
        \'quarantine\'    => $location4Quarantine,
        \'disabled\'      => $location4Disabled ?:\'[]\',
        \'startPath\'     => $startPath4,
        \'accessControl\' => $myModxPermission4,
        \'attributes\'    => $usPer4 ?: \'[]\'
      );
    $loc4 = $modx->getChunk(\'elfinderLocalFileSystem4\'.$suffix , $loc4Properties);
    if($loc4){
        $loc4 = jDecode($loc4);
    }
}

if($user->isMember(listArray($uSGr5)) && $location5) {
    if (strpos($getLocation, \'5\') !== false){
        $myModxPermission5 = \'hideThis\';
    }
    $loc5Properties = array(
        \'id\'            =>\'loc5\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location5,
        \'path\'          => $location5Path,
        \'url\'           => $location5Url,
        \'tmbPath\'       => $location5tmbPath,
        \'quarantine\'    => $location5Quarantine,
        \'disabled\'      => $location5Disabled ?:\'[]\',
        \'startPath\'     => $startPath5,
        \'accessControl\' => $myModxPermission5,
        \'attributes\'    => $usPer5 ?: \'[]\'
      );
    $loc5 = $modx->getChunk(\'elfinderLocalFileSystem5\'.$suffix , $loc5Properties);
    if($loc5){
        $loc5 = jDecode($loc5);
    }
}
if($user->isMember(listArray($uSGrPersonal)) && $enablePersonalFolder) {
    if (strpos($getLocation, \'p\') !== false){
        $myModxPermissionP = \'hideThis\';
    }

    $myPersonalFolderName = $modx->runSnippet($twAutoCreatePersonalFolders, array(\'path\'=>$locationPersonalPath));

    $locPersonalProperties = array(
        \'id\'            => \'locP\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $locationPersonal,
        \'path\'          => $locationPersonalPath.$myPersonalFolderName.\'/\',
        \'url\'           => $locationPersonalUrl.$myPersonalFolderName.\'/\',
        \'tmbPath\'       => $locationPersonaltmbPath,
        \'quarantine\'    => $locationPersonalQuarantine,
        \'disabled\'      => $locationPersonalDisabled ?:\'[]\',
        \'startPath\'     => $startPathPersonal,
        \'accessControl\' => $myModxPermissionP,
        \'attributes\'    => $usPerPersonal ?: \'[]\'
      );
    $locPersonal = $modx->getChunk(\'elfinderLocalFileSystemPersonal\'.$suffix , $locPersonalProperties);
    if($locPersonal){
        $locPersonal = jDecode($locPersonal);
    }
}

$opts = array(\'roots\' => array($loc1,$loc2,$loc3,$loc4,$loc5,$locPersonal,$ftp1));
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();',
      'locked' => 0,
      'properties' => 'a:55:{s:11:"chunkSuffix";a:7:{s:4:"name";s:11:"chunkSuffix";s:4:"desc";s:282:"This snippet calls its own chunks for you; it will not override them once created, but you were better off duplicating them.
PLEASE simply add a suffix (_test or -su) to your new name.
elfinderLocalFileSystem4 becomes elfinderLocalFileSystem4-test or elfinderLocalFileSystem4-suffix";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:20:"00 elFinder Security";}s:16:"myModxPermission";a:7:{s:4:"name";s:16:"myModxPermission";s:4:"desc";s:242:"Super folder control. Although each location can have own permission, this option here allows you to mass lock all.
Only an admin of user id that is listed in sudoAdminIDs can unlock all folders via URL parameter e.g elfinder.html?unlocked=1 ";s:4:"type";s:4:"list";s:7:"options";a:3:{i:0;a:2:{s:4:"text";s:39:"Lock up all folders (but still visible)";s:5:"value";s:16:"lockedButVisible";}i:1;a:2:{s:4:"text";s:26:"Default Setting (unlocked)";s:5:"value";s:10:"forMembers";}i:2;a:2:{s:4:"text";s:16:"Hide all folders";s:5:"value";s:8:"hideThis";}}s:5:"value";s:16:"lockedButVisible";s:7:"lexicon";N;s:4:"area";s:20:"00 elFinder Security";}s:9:"sudoAdmin";a:7:{s:4:"name";s:9:"sudoAdmin";s:4:"desc";s:130:"Disable location?AccessPermission security settings for Administrator userGroup member whose userid that is found in sudoAdminIDs.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:20:"00 elFinder Security";}s:12:"sudoAdminIDs";a:7:{s:4:"name";s:12:"sudoAdminIDs";s:4:"desc";s:87:"Comma-separated list of ultimate admin user ids with special elFinder unlock privileges";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:1:"1";s:7:"lexicon";N;s:4:"area";s:20:"00 elFinder Security";}s:13:"sudoUserGroup";a:7:{s:4:"name";s:13:"sudoUserGroup";s:4:"desc";s:90:"Comma-separated list of ultimate admin user groups with special elFinder unlock privileges";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Administrator";s:7:"lexicon";N;s:4:"area";s:20:"00 elFinder Security";}s:15:"twCreateFolders";a:7:{s:4:"name";s:15:"twCreateFolders";s:4:"desc";s:108:"Name of snippet to use in order to create personal folders for users. Default is autoCreateFoldersTWelfinder";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:27:"autoCreateFoldersTWelfinder";s:7:"lexicon";N;s:4:"area";s:20:"00 elFinder Security";}s:9:"location1";a:7:{s:4:"name";s:9:"location1";s:4:"desc";s:143:"elFinder alias. Identify this location. To disable this location, leave empty. location1, being the first in array, will be the default volume.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:10:"FileSystem";s:7:"lexicon";N;s:4:"area";s:12:"01 location1";}s:25:"location1AccessPermission";a:7:{s:4:"name";s:25:"location1AccessPermission";s:4:"desc";s:316:"elFinder attributes. Use to protect folder and files. Use JSON format here are examples
[{"pattern":"\\/\\/","read":true,"write":false,"locked":true},{"pattern":"\\/core\\/","read":false,"write":false,"hidden":true},{"pattern":"\\/.tmb*\\/","hidden":true},{"pattern":"\\/*.php/$\\/","locked":false,"write":true,"read":true}]";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"01 location1";}s:24:"location1AccessUserGroup";a:7:{s:4:"name";s:24:"location1AccessUserGroup";s:4:"desc";s:66:"Comma-separated list of user groups that can access this location.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Administrator";s:7:"lexicon";N;s:4:"area";s:12:"01 location1";}s:17:"location1Disabled";a:7:{s:4:"name";s:17:"location1Disabled";s:4:"desc";s:345:"elFinder disabled commands. JSON list of detailed activities to deny a user in this location: ["open", "reload", "home", "up", "back", "forward", "getfile", "quicklook", "download", "rm", "duplicate", "rename", "mkdir", "mkfile", "upload", "copy", "cut", "paste", "edit", "extract", "archive", "search", "info", "view", "help", "resize", "sort"]";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"01 location1";}s:13:"location1Path";a:7:{s:4:"name";s:13:"location1Path";s:4:"desc";s:63:"elFinder path. Location to open/start from. Root upload folder.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:15:"[[++base_path]]";s:7:"lexicon";N;s:4:"area";s:12:"01 location1";}s:19:"location1Quarantine";a:7:{s:4:"name";s:19:"location1Quarantine";s:4:"desc";s:146:"elFinder quarantine (Example: .quarantine). Temporary directory for archive file extracting. We recommend to set a path outside the document root.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"01 location1";}s:12:"location1Url";a:7:{s:4:"name";s:12:"location1Url";s:4:"desc";s:131:"elFinder URL. This is used/needed to find file paths in say, TinyMCE. To hide the location of files, leave empty (not recommended).";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:14:"[[++base_url]]";s:7:"lexicon";N;s:4:"area";s:12:"01 location1";}s:16:"location1tmbPath";a:7:{s:4:"name";s:16:"location1tmbPath";s:4:"desc";s:370:"elFinder tmbPath. This is will create .tmb folder in the root upload folder that is, location?Path. This folder is used to store elFinder thumbnails for images and the like. You may leave empty if this is too much trouble to you. If ever you use another file browser like Responsive FManager you may want to hide these folders (.tmb etc) which elFinder hides by default.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:4:".tmb";s:7:"lexicon";N;s:4:"area";s:12:"01 location1";}s:9:"location2";a:7:{s:4:"name";s:9:"location2";s:4:"desc";s:78:"elFinder alias. Identify this location. To disable this location, leave empty.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Assets Folder";s:7:"lexicon";N;s:4:"area";s:12:"02 location2";}s:25:"location2AccessPermission";a:7:{s:4:"name";s:25:"location2AccessPermission";s:4:"desc";s:316:"elFinder attributes. Use to protect folder and files. Use JSON format here are examples
[{"pattern":"\\/\\/","read":true,"write":false,"locked":true},{"pattern":"\\/core\\/","read":false,"write":false,"hidden":true},{"pattern":"\\/.tmb*\\/","hidden":true},{"pattern":"\\/*.php/$\\/","locked":false,"write":true,"read":true}]";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"02 location2";}s:24:"location2AccessUserGroup";a:7:{s:4:"name";s:24:"location2AccessUserGroup";s:4:"desc";s:66:"Comma-separated list of user groups that can access this location.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Administrator";s:7:"lexicon";N;s:4:"area";s:12:"02 location2";}s:17:"location2Disabled";a:7:{s:4:"name";s:17:"location2Disabled";s:4:"desc";s:345:"elFinder disabled commands. JSON list of detailed activities to deny a user in this location: ["open", "reload", "home", "up", "back", "forward", "getfile", "quicklook", "download", "rm", "duplicate", "rename", "mkdir", "mkfile", "upload", "copy", "cut", "paste", "edit", "extract", "archive", "search", "info", "view", "help", "resize", "sort"]";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"02 location2";}s:13:"location2Path";a:7:{s:4:"name";s:13:"location2Path";s:4:"desc";s:63:"elFinder path. Location to open/start from. Root upload folder.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:17:"[[++assets_path]]";s:7:"lexicon";N;s:4:"area";s:12:"02 location2";}s:19:"location2Quarantine";a:7:{s:4:"name";s:19:"location2Quarantine";s:4:"desc";s:146:"elFinder quarantine (Example: .quarantine). Temporary directory for archive file extracting. We recommend to set a path outside the document root.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"02 location2";}s:12:"location2Url";a:7:{s:4:"name";s:12:"location2Url";s:4:"desc";s:131:"elFinder URL. This is used/needed to find file paths in say, TinyMCE. To hide the location of files, leave empty (not recommended).";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:16:"[[++assets_url]]";s:7:"lexicon";N;s:4:"area";s:12:"02 location2";}s:16:"location2tmbPath";a:7:{s:4:"name";s:16:"location2tmbPath";s:4:"desc";s:370:"elFinder tmbPath. This is will create .tmb folder in the root upload folder that is, location?Path. This folder is used to store elFinder thumbnails for images and the like. You may leave empty if this is too much trouble to you. If ever you use another file browser like Responsive FManager you may want to hide these folders (.tmb etc) which elFinder hides by default.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"02 location2";}s:9:"location3";a:7:{s:4:"name";s:9:"location3";s:4:"desc";s:78:"elFinder alias. Identify this location. To disable this location, leave empty.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:11:"Core Folder";s:7:"lexicon";N;s:4:"area";s:12:"03 location3";}s:25:"location3AccessPermission";a:7:{s:4:"name";s:25:"location3AccessPermission";s:4:"desc";s:316:"elFinder attributes. Use to protect folder and files. Use JSON format here are examples
[{"pattern":"\\/\\/","read":true,"write":false,"locked":true},{"pattern":"\\/core\\/","read":false,"write":false,"hidden":true},{"pattern":"\\/.tmb*\\/","hidden":true},{"pattern":"\\/*.php/$\\/","locked":false,"write":true,"read":true}]";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"03 location3";}s:24:"location3AccessUserGroup";a:7:{s:4:"name";s:24:"location3AccessUserGroup";s:4:"desc";s:66:"Comma-separated list of user groups that can access this location.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Administrator";s:7:"lexicon";N;s:4:"area";s:12:"03 location3";}s:17:"location3Disabled";a:7:{s:4:"name";s:17:"location3Disabled";s:4:"desc";s:345:"elFinder disabled commands. JSON list of detailed activities to deny a user in this location: ["open", "reload", "home", "up", "back", "forward", "getfile", "quicklook", "download", "rm", "duplicate", "rename", "mkdir", "mkfile", "upload", "copy", "cut", "paste", "edit", "extract", "archive", "search", "info", "view", "help", "resize", "sort"]";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"03 location3";}s:13:"location3Path";a:7:{s:4:"name";s:13:"location3Path";s:4:"desc";s:63:"elFinder path. Location to open/start from. Root upload folder.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:15:"[[++core_path]]";s:7:"lexicon";N;s:4:"area";s:12:"03 location3";}s:19:"location3Quarantine";a:7:{s:4:"name";s:19:"location3Quarantine";s:4:"desc";s:146:"elFinder quarantine (Example: .quarantine). Temporary directory for archive file extracting. We recommend to set a path outside the document root.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"03 location3";}s:12:"location3Url";a:7:{s:4:"name";s:12:"location3Url";s:4:"desc";s:131:"elFinder URL. This is used/needed to find file paths in say, TinyMCE. To hide the location of files, leave empty (not recommended).";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"03 location3";}s:16:"location3tmbPath";a:7:{s:4:"name";s:16:"location3tmbPath";s:4:"desc";s:370:"elFinder tmbPath. This is will create .tmb folder in the root upload folder that is, location?Path. This folder is used to store elFinder thumbnails for images and the like. You may leave empty if this is too much trouble to you. If ever you use another file browser like Responsive FManager you may want to hide these folders (.tmb etc) which elFinder hides by default.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"03 location3";}s:9:"location4";a:7:{s:4:"name";s:9:"location4";s:4:"desc";s:78:"elFinder alias. Identify this location. To disable this location, leave empty.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"Cache Folder";s:7:"lexicon";N;s:4:"area";s:12:"04 location4";}s:25:"location4AccessPermission";a:7:{s:4:"name";s:25:"location4AccessPermission";s:4:"desc";s:316:"elFinder attributes. Use to protect folder and files. Use JSON format here are examples
[{"pattern":"\\/\\/","read":true,"write":false,"locked":true},{"pattern":"\\/core\\/","read":false,"write":false,"hidden":true},{"pattern":"\\/.tmb*\\/","hidden":true},{"pattern":"\\/*.php/$\\/","locked":false,"write":true,"read":true}]";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"04 location4";}s:24:"location4AccessUserGroup";a:7:{s:4:"name";s:24:"location4AccessUserGroup";s:4:"desc";s:66:"Comma-separated list of user groups that can access this location.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Administrator";s:7:"lexicon";N;s:4:"area";s:12:"04 location4";}s:17:"location4Disabled";a:7:{s:4:"name";s:17:"location4Disabled";s:4:"desc";s:345:"elFinder disabled commands. JSON list of detailed activities to deny a user in this location: ["open", "reload", "home", "up", "back", "forward", "getfile", "quicklook", "download", "rm", "duplicate", "rename", "mkdir", "mkfile", "upload", "copy", "cut", "paste", "edit", "extract", "archive", "search", "info", "view", "help", "resize", "sort"]";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"04 location4";}s:13:"location4Path";a:7:{s:4:"name";s:13:"location4Path";s:4:"desc";s:63:"elFinder path. Location to open/start from. Root upload folder.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:21:"[[++core_path]]cache/";s:7:"lexicon";N;s:4:"area";s:12:"04 location4";}s:19:"location4Quarantine";a:7:{s:4:"name";s:19:"location4Quarantine";s:4:"desc";s:146:"elFinder quarantine (Example: .quarantine). Temporary directory for archive file extracting. We recommend to set a path outside the document root.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"04 location4";}s:12:"location4Url";a:7:{s:4:"name";s:12:"location4Url";s:4:"desc";s:131:"elFinder URL. This is used/needed to find file paths in say, TinyMCE. To hide the location of files, leave empty (not recommended).";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"04 location4";}s:16:"location4tmbPath";a:7:{s:4:"name";s:16:"location4tmbPath";s:4:"desc";s:370:"elFinder tmbPath. This is will create .tmb folder in the root upload folder that is, location?Path. This folder is used to store elFinder thumbnails for images and the like. You may leave empty if this is too much trouble to you. If ever you use another file browser like Responsive FManager you may want to hide these folders (.tmb etc) which elFinder hides by default.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"04 location4";}s:9:"location5";a:7:{s:4:"name";s:9:"location5";s:4:"desc";s:78:"elFinder alias. Identify this location. To disable this location, leave empty.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:14:"Manager Folder";s:7:"lexicon";N;s:4:"area";s:12:"05 location5";}s:25:"location5AccessPermission";a:7:{s:4:"name";s:25:"location5AccessPermission";s:4:"desc";s:316:"elFinder attributes. Use to protect folder and files. Use JSON format here are examples
[{"pattern":"\\/\\/","read":true,"write":false,"locked":true},{"pattern":"\\/core\\/","read":false,"write":false,"hidden":true},{"pattern":"\\/.tmb*\\/","hidden":true},{"pattern":"\\/*.php/$\\/","locked":false,"write":true,"read":true}]";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"05 location5";}s:24:"location5AccessUserGroup";a:7:{s:4:"name";s:24:"location5AccessUserGroup";s:4:"desc";s:66:"Comma-separated list of user groups that can access this location.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Administrator";s:7:"lexicon";N;s:4:"area";s:12:"05 location5";}s:17:"location5Disabled";a:7:{s:4:"name";s:17:"location5Disabled";s:4:"desc";s:345:"elFinder disabled commands. JSON list of detailed activities to deny a user in this location: ["open", "reload", "home", "up", "back", "forward", "getfile", "quicklook", "download", "rm", "duplicate", "rename", "mkdir", "mkfile", "upload", "copy", "cut", "paste", "edit", "extract", "archive", "search", "info", "view", "help", "resize", "sort"]";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"05 location5";}s:13:"location5Path";a:7:{s:4:"name";s:13:"location5Path";s:4:"desc";s:63:"elFinder path. Location to open/start from. Root upload folder.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:18:"[[++manager_path]]";s:7:"lexicon";N;s:4:"area";s:12:"05 location5";}s:19:"location5Quarantine";a:7:{s:4:"name";s:19:"location5Quarantine";s:4:"desc";s:146:"elFinder quarantine (Example: .quarantine). Temporary directory for archive file extracting. We recommend to set a path outside the document root.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"05 location5";}s:12:"location5Url";a:7:{s:4:"name";s:12:"location5Url";s:4:"desc";s:131:"elFinder URL. This is used/needed to find file paths in say, TinyMCE. To hide the location of files, leave empty (not recommended).";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:17:"[[++manager_url]]";s:7:"lexicon";N;s:4:"area";s:12:"05 location5";}s:16:"location5tmbPath";a:7:{s:4:"name";s:16:"location5tmbPath";s:4:"desc";s:370:"elFinder tmbPath. This is will create .tmb folder in the root upload folder that is, location?Path. This folder is used to store elFinder thumbnails for images and the like. You may leave empty if this is too much trouble to you. If ever you use another file browser like Responsive FManager you may want to hide these folders (.tmb etc) which elFinder hides by default.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:12:"05 location5";}s:20:"enablePersonalFolder";a:7:{s:4:"name";s:20:"enablePersonalFolder";s:4:"desc";s:195:"If you want a browsing user to have a folder created, so that this new folder becomes their root folder (the default folder name is username. Please configure autoCreateFoldersTWelfinder snippet.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:15:"Personal Folder";}s:16:"locationPersonal";a:7:{s:4:"name";s:16:"locationPersonal";s:4:"desc";s:14:"elFinder alias";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:15:"Personal Folder";s:7:"lexicon";N;s:4:"area";s:15:"Personal Folder";}s:32:"locationPersonalAccessPermission";a:7:{s:4:"name";s:32:"locationPersonalAccessPermission";s:4:"desc";s:316:"elFinder attributes. Use to protect folder and files. Use JSON format here are examples
[{"pattern":"\\/\\/","read":true,"write":false,"locked":true},{"pattern":"\\/core\\/","read":false,"write":false,"hidden":true},{"pattern":"\\/.tmb*\\/","hidden":true},{"pattern":"\\/*.php/$\\/","locked":false,"write":true,"read":true}]";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:15:"Personal Folder";}s:31:"locationPersonalAccessUserGroup";a:7:{s:4:"name";s:31:"locationPersonalAccessUserGroup";s:4:"desc";s:116:"Comma-separated list of usergroups - Whether the user has to belong to a particular user group to enjoy this feature";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Administrator";s:7:"lexicon";N;s:4:"area";s:15:"Personal Folder";}s:24:"locationPersonalDisabled";a:7:{s:4:"name";s:24:"locationPersonalDisabled";s:4:"desc";s:345:"elFinder disabled commands. JSON list of detailed activities to deny a user in this location: ["open", "reload", "home", "up", "back", "forward", "getfile", "quicklook", "download", "rm", "duplicate", "rename", "mkdir", "mkfile", "upload", "copy", "cut", "paste", "edit", "extract", "archive", "search", "info", "view", "help", "resize", "sort"]";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:15:"Personal Folder";}s:20:"locationPersonalPath";a:7:{s:4:"name";s:20:"locationPersonalPath";s:4:"desc";s:69:"Under what parent folder is the user personal folder(s) to be created";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:15:"[[++base_path]]";s:7:"lexicon";N;s:4:"area";s:15:"Personal Folder";}s:26:"locationPersonalQuarantine";a:7:{s:4:"name";s:26:"locationPersonalQuarantine";s:4:"desc";s:146:"elFinder quarantine (Example: .quarantine). Temporary directory for archive file extracting. We recommend to set a path outside the document root.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:15:"Personal Folder";}s:19:"locationPersonalUrl";a:7:{s:4:"name";s:19:"locationPersonalUrl";s:4:"desc";s:131:"elFinder URL. This is used/needed to find file paths in say, TinyMCE. To hide the location of files, leave empty (not recommended).";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:14:"[[++base_url]]";s:7:"lexicon";N;s:4:"area";s:15:"Personal Folder";}s:23:"locationPersonaltmbPath";a:7:{s:4:"name";s:23:"locationPersonaltmbPath";s:4:"desc";s:370:"elFinder tmbPath. This is will create .tmb folder in the root upload folder that is, location?Path. This folder is used to store elFinder thumbnails for images and the like. You may leave empty if this is too much trouble to you. If ever you use another file browser like Responsive FManager you may want to hide these folders (.tmb etc) which elFinder hides by default.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:15:"Personal Folder";}}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '/*TinymceWrapper elfinderConnector is called by assets/components/tinymcewrapper/browserConnectors/elfinder.php
To use a MODx resource instead of this external file,
simply create your own resource, with empty template and call [[!elfinderConnector@myPropertySet]],
insert the new resource\'s url in your elFinder.html init, thus replacing the old one.

-------------------Roadmap:
-Add dropbox and AWS
-Add Media Source for File/Image TVs
-Look for more ways to be awesome;
---------------------------

Use freely, recode freely, report freely, enjoy freely
Dedicated to those who have cried
---------------------------

http://www.leofec.com/modx-revolution/
-donshakespeare in the MODx forum
*/
error_reporting(0); // Set E_ALL for debuging

include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderConnector.class.php\';
include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinder.class.php\';
include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderVolumeDriver.class.php\';
include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderVolumeLocalFileSystem.class.php\';
// Required for MySQL storage connector
// include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderVolumeMySQL.class.php\';
// Required for FTP connector support
include_once MODX_ASSETS_PATH.\'components/tinymcewrapper/elfinder/php/elFinderVolumeFTP.class.php\';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  \'.\' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/

$sp = $scriptProperties;
$getParam = \'\';
$getLocation = getMyParams(\'hide\');

$user = $modx->getObject(\'modUser\', array(\'id\' => $modx->user->get(\'id\'))); // get present user infos

$suffix = $modx->getOption(\'chunkSuffix\', $sp);
$enablePersonalFolder = $modx->getOption(\'enablePersonalFolder\', $sp);
$sudoAdmin = $modx->getOption(\'sudoAdmin\', $sp);
$sudoUserGroup = $modx->getOption(\'sudoUserGroup\', $sp);

$sudoAdminIDs = listArray($modx->getOption(\'sudoAdminIDs\', $sp, array()));

$twAutoCreatePersonalFolders = $modx->getOption(\'twAutoCreatePersonalFolders\', $sp);

$uSGr1 = $modx->getOption(\'location1AccessUserGroup\', $sp);
$uSGr2 = $modx->getOption(\'location2AccessUserGroup\', $sp);
$uSGr3 = $modx->getOption(\'location3AccessUserGroup\', $sp);
$uSGr4 = $modx->getOption(\'location4AccessUserGroup\', $sp);
$uSGr5 = $modx->getOption(\'location5AccessUserGroup\', $sp);
$uSGrPersonal = $modx->getOption(\'locationPersonalAccessUserGroup\', $sp);

if(!$user->isMember(listArray($sudoUserGroup.\',\'.$uSGr1.\',\'.$uSGr2.\',\'.$uSGr3.\',\'.$uSGr4.\',\'.$uSGrPersonal))) die;

$location1 = $modx->getOption(\'location1\', $sp);
$location2 = $modx->getOption(\'location2\', $sp);
$location3 = $modx->getOption(\'location3\', $sp);
$location4 = $modx->getOption(\'location4\', $sp);
$location5 = $modx->getOption(\'location5\', $sp);
$locationPersonal = $modx->getOption(\'locationPersonal\', $sp);

$location1Path = $modx->getOption(\'location1Path\', $sp);
$location2Path = $modx->getOption(\'location2Path\', $sp);
$location3Path = $modx->getOption(\'location3Path\', $sp);
$location4Path = $modx->getOption(\'location4Path\', $sp);
$location5Path = $modx->getOption(\'location5Path\', $sp);
$locationPersonalPath = $modx->getOption(\'locationPersonalPath\', $sp);

$location1Url = $modx->getOption(\'location1Url\', $sp);
$location2Url = $modx->getOption(\'location2Url\', $sp);
$location3Url = $modx->getOption(\'location3Url\', $sp);
$location4Url = $modx->getOption(\'location4Url\', $sp);
$location5Url = $modx->getOption(\'location5Url\', $sp);
$locationPersonalUrl = $modx->getOption(\'locationPersonalUrl\', $sp);

$location1tmbPath = $modx->getOption(\'location1tmbPath\', $sp);
$location2tmbPath = $modx->getOption(\'location2tmbPath\', $sp);
$location3tmbPath = $modx->getOption(\'location3tmbPath\', $sp);
$location4tmbPath = $modx->getOption(\'location4tmbPath\', $sp);
$location5tmbPath = $modx->getOption(\'location5tmbPath\', $sp);
$locationPersonaltmbPath = $modx->getOption(\'locationPersonaltmbPath\', $sp);

$location1Quarantine = $modx->getOption(\'location1Quarantine\', $sp);
$location2Quarantine = $modx->getOption(\'location2Quarantine\', $sp);
$location3Quarantine = $modx->getOption(\'location3Quarantine\', $sp);
$location4Quarantine = $modx->getOption(\'location4Quarantine\', $sp);
$location5Quarantine = $modx->getOption(\'location5Quarantine\', $sp);
$locationPersonalQuarantine = $modx->getOption(\'locationPersonalQuarantine\', $sp);

$location1Disabled = $modx->getOption(\'location1Disabled\', $sp);
$location2Disabled = $modx->getOption(\'location2Disabled\', $sp);
$location3Disabled = $modx->getOption(\'location3Disabled\', $sp);
$location4Disabled = $modx->getOption(\'location4Disabled\', $sp);
$location5Disabled = $modx->getOption(\'location5Disabled\', $sp);
$locationPersonalDisabled = $modx->getOption(\'locationPersonalDisabled\', $sp);

$usPer1 = $modx->getOption(\'location1AccessPermission\', $sp);
$usPer2 = $modx->getOption(\'location2AccessPermission\', $sp);
$usPer3 = $modx->getOption(\'location3AccessPermission\', $sp);
$usPer4 = $modx->getOption(\'location4AccessPermission\', $sp);
$usPer5 = $modx->getOption(\'location5AccessPermission\', $sp);
$usPerPersonal = $modx->getOption(\'locationPersonalAccessPermission\', $sp);

function getMyParams($name){
  if (isset($_GET[$name]) && !empty($_GET[$name])) {
    $getParam = filter_var($_GET[$name], FILTER_SANITIZE_STRING);
    $getParam = rawurldecode($getParam);
    return $getParam;
  }
 }

function forMembers($attr, $path, $data, $volume) {
    if (strpos(basename($path), \'.\') === 0){ //hide .tmb folders
      return (!($attr == \'read\' || $attr == \'write\'));
    }
    //example of what you can do
   /* if (strpos(basename($path), \'core\') === 0 || strpos(basename($path), \'manager\') === 0){
      return ($attr !== \'read\' && $attr == \'locked\');
    }*/
    // if($onlyAdministratorMembers == 1){
    //     if (strpos(dirname($path), \'/\') !== false){
    //       return !(($attr == \'read\' || $attr == \'write\'));
    //       // return ($attr == \'read\' || $attr == \'locked\' || $attr !== \'write\');
    //     }
    // }
}

function lockedButVisible($attr, $path, $data, $volume) {
    if (strpos(basename($path), \'.\') === 0){ //hide .tmb folders
      return (!($attr == \'read\' || $attr == \'write\'));
    }
    if (strpos(dirname($path), \'/\') !== false){
         return ($attr == \'read\' || $attr == \'locked\');
    }
}

function forAdmin($attr, $path, $data, $volume) {
    if (strpos(basename($path), \'.\') === 0){ //hide all .tmb folders
      return (!($attr == \'read\' || $attr == \'write\'));
    }
}

function hideThis($attr, $path, $data, $volume) {
    if (strpos(dirname($path), \'/\') !== false){
         return (!($attr == \'read\' || $attr == \'write\'));
    }
}

function listArray($thisList){
    $thisList = preg_replace(\'/\\s+/\', \'\', $thisList);
    $thisList = preg_replace("/[^a-z0-9,-_]+/i", \' \', $thisList);
    $thisList = explode(\',\', $thisList);
    return $thisList;
}

function jDecode($item){
    $json = $item;
    $json = json_decode($json, true);
    return $json;
}

if($sudoAdmin && $user->isMember(listArray($sudoUserGroup))) {
    $usPer1 = $usPer2 = $usPer3 = $usPer4 = $usPerPersonal = \'[]\';
}

$loc1 = $loc2 = $loc3 = $loc4 = $loc5 = $locPersonal =  array();


// $myModxPermission = \'forMembers\';
$myModxPermission = $modx->getOption(\'myModxPermission\', $sp);

// if($user->isMember(listArray($sudoUserGroup)) && getMyParams(\'unlocked\') == 1) {
if(in_array($modx->user->get(\'id\'), $sudoAdminIDs) && getMyParams(\'unlocked\') == 1) {
    $myModxPermission =\'forAdmin\';
}

$myModxPermission1 = $myModxPermission2 = $myModxPermission3 = $myModxPermission4 = $myModxPermission5 = $myModxPermissionP = $myModxPermission;

$startPath1 = $modx->getOption(\'location1Path\', $sp).getMyParams(\'folder\');
$startPath2 = $modx->getOption(\'location2Path\', $sp).getMyParams(\'folder\');
$startPath3 = $modx->getOption(\'location3Path\', $sp).getMyParams(\'folder\');
$startPath4 = $modx->getOption(\'location4Path\', $sp).getMyParams(\'folder\');
$startPath5 = $modx->getOption(\'location5Path\', $sp).getMyParams(\'folder\');
$startPathPersonal = $modx->getOption(\'locationPersonalPath\', $sp).getMyParams(\'folder\');

//add as many fldrs/locations as you like -- see docs for more wonderful options
//check if browsing user can indeed access this location
//check if this location has a name or it is disabled by being rendered nameless
if($user->isMember(listArray($uSGr1)) && $location1) {
    if (strpos($getLocation, \'1\') !== false){
        $myModxPermission1 = \'hideThis\';
}
    $loc1Properties = array(
        \'id\'            =>\'loc1\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location1,
        \'path\'          => $location1Path,
        \'url\'           => $location1Url,
        \'tmbPath\'       => $location1tmbPath,
        \'quarantine\'    => $location1Quarantine,
        \'disabled\'      => $location1Disabled ?:\'[]\',
        \'startPath\'     => $startPath1,
        \'accessControl\' => $myModxPermission1,
        \'attributes\'    => $usPer1 ?: \'[]\'
      );
    $loc1 = $modx->getChunk(\'elfinderLocalFileSystem1\'.$suffix , $loc1Properties);
    if($loc1){
        $loc1 = jDecode($loc1);
    }
}

if($user->isMember(listArray($uSGr2)) && $location2) {
    if (strpos($getLocation, \'2\') !== false){
        $myModxPermission2 = \'hideThis\';
    }
    $loc2Properties = array(
        \'id\'            =>\'loc2\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location2,
        \'path\'          => $location2Path,
        \'url\'           => $location2Url,
        \'tmbPath\'       => $location2tmbPath,
        \'quarantine\'    => $location2Quarantine,
        \'disabled\'      => $location2Disabled ?:\'[]\',
        \'startPath\'     => $startPath2,
        \'accessControl\' => $myModxPermission2,
        \'attributes\'    => $usPer2 ?: \'[]\'
      );
    $loc2 = $modx->getChunk(\'elfinderLocalFileSystem2\'.$suffix , $loc2Properties);
    if($loc2){
        $loc2 = jDecode($loc2);
    }
}

if($user->isMember(listArray($uSGr3)) && $location3) {
    if (strpos($getLocation, \'3\') !== false){
        $myModxPermission3 = \'hideThis\';
    }
    $loc3Properties = array(
        \'id\'            =>\'loc3\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location3,
        \'path\'          => $location3Path,
        \'url\'           => $location3Url,
        \'tmbPath\'       => $location3tmbPath,
        \'quarantine\'    => $location3Quarantine,
        \'disabled\'      => $location3Disabled ?:\'[]\',
        \'startPath\'     => $startPath3,
        \'accessControl\' => $myModxPermission3,
        \'attributes\'    => $usPer3 ?: \'[]\'
      );
    $loc3 = $modx->getChunk(\'elfinderLocalFileSystem3\'.$suffix , $loc3Properties);
    if($loc3){
        $loc3 = jDecode($loc3);
    }
}

if($user->isMember(listArray($uSGr4)) && $location4) {
    if (strpos($getLocation, \'4\') !== false){
        $myModxPermission4 = \'hideThis\';
    }
    $loc4Properties = array(
        \'id\'            =>\'loc4\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location4,
        \'path\'          => $location4Path,
        \'url\'           => $location4Url,
        \'tmbPath\'       => $location4tmbPath,
        \'quarantine\'    => $location4Quarantine,
        \'disabled\'      => $location4Disabled ?:\'[]\',
        \'startPath\'     => $startPath4,
        \'accessControl\' => $myModxPermission4,
        \'attributes\'    => $usPer4 ?: \'[]\'
      );
    $loc4 = $modx->getChunk(\'elfinderLocalFileSystem4\'.$suffix , $loc4Properties);
    if($loc4){
        $loc4 = jDecode($loc4);
    }
}

if($user->isMember(listArray($uSGr5)) && $location5) {
    if (strpos($getLocation, \'5\') !== false){
        $myModxPermission5 = \'hideThis\';
    }
    $loc5Properties = array(
        \'id\'            =>\'loc5\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $location5,
        \'path\'          => $location5Path,
        \'url\'           => $location5Url,
        \'tmbPath\'       => $location5tmbPath,
        \'quarantine\'    => $location5Quarantine,
        \'disabled\'      => $location5Disabled ?:\'[]\',
        \'startPath\'     => $startPath5,
        \'accessControl\' => $myModxPermission5,
        \'attributes\'    => $usPer5 ?: \'[]\'
      );
    $loc5 = $modx->getChunk(\'elfinderLocalFileSystem5\'.$suffix , $loc5Properties);
    if($loc5){
        $loc5 = jDecode($loc5);
    }
}
if($user->isMember(listArray($uSGrPersonal)) && $enablePersonalFolder) {
    if (strpos($getLocation, \'p\') !== false){
        $myModxPermissionP = \'hideThis\';
    }

    $myPersonalFolderName = $modx->runSnippet($twAutoCreatePersonalFolders, array(\'path\'=>$locationPersonalPath));

    $locPersonalProperties = array(
        \'id\'            => \'locP\',
        \'driver\'        => \'LocalFileSystem\',
        \'alias\'         => $locationPersonal,
        \'path\'          => $locationPersonalPath.$myPersonalFolderName.\'/\',
        \'url\'           => $locationPersonalUrl.$myPersonalFolderName.\'/\',
        \'tmbPath\'       => $locationPersonaltmbPath,
        \'quarantine\'    => $locationPersonalQuarantine,
        \'disabled\'      => $locationPersonalDisabled ?:\'[]\',
        \'startPath\'     => $startPathPersonal,
        \'accessControl\' => $myModxPermissionP,
        \'attributes\'    => $usPerPersonal ?: \'[]\'
      );
    $locPersonal = $modx->getChunk(\'elfinderLocalFileSystemPersonal\'.$suffix , $locPersonalProperties);
    if($locPersonal){
        $locPersonal = jDecode($locPersonal);
    }
}

$opts = array(\'roots\' => array($loc1,$loc2,$loc3,$loc4,$loc5,$locPersonal,$ftp1));
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();',
    ),
  ),
  '01ca00dc11711257b165ba1f54939441' => 
  array (
    'criteria' => 
    array (
      'name' => 'autoCreateFoldersTWelfinder',
    ),
    'object' => 
    array (
      'id' => 2,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'autoCreateFoldersTWelfinder',
      'description' => 'This snippet auto creates folders for each user',
      'editor_type' => 0,
      'category' => 3,
      'cache_type' => 0,
      'snippet' => '//if you wish to encrypt this name and/or use another userfield, do it here
$myPersonal= $modx->user->get(\'username\');
/////////////////////////////////////////////

//$path is supplied by the calling connector, elFinder or responsivefilemanager
if (!file_exists($path.$myPersonal.\'/\')) {
    mkdir($path.$myPersonal.\'/\', 0755, true);
}

//at this time, you may create as many subfolders as you want under $myPersonal
if (!file_exists($path.$myPersonal.\'/media/\')) {
    mkdir($path.$myPersonal.\'/media/\', 0755, true);
}

return $myPersonal;',
      'locked' => 0,
      'properties' => NULL,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '//if you wish to encrypt this name and/or use another userfield, do it here
$myPersonal= $modx->user->get(\'username\');
/////////////////////////////////////////////

//$path is supplied by the calling connector, elFinder or responsivefilemanager
if (!file_exists($path.$myPersonal.\'/\')) {
    mkdir($path.$myPersonal.\'/\', 0755, true);
}

//at this time, you may create as many subfolders as you want under $myPersonal
if (!file_exists($path.$myPersonal.\'/media/\')) {
    mkdir($path.$myPersonal.\'/media/\', 0755, true);
}

return $myPersonal;',
    ),
  ),
  '7ab88cdb9014caf1a67ae41920a578e9' => 
  array (
    'criteria' => 
    array (
      'category' => 'ResponsiveFileManager',
    ),
    'object' => 
    array (
      'id' => 4,
      'parent' => 2,
      'category' => 'ResponsiveFileManager',
      'rank' => 0,
    ),
  ),
  '20df2679ba35a6c6c1666f05e825efc2' => 
  array (
    'criteria' => 
    array (
      'name' => 'responsivePersonalConfig',
    ),
    'object' => 
    array (
      'id' => 7,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'responsivePersonalConfig',
      'description' => 'This template will be used to create a personal config.php file for each user if you activate activatePersonalFolders',
      'editor_type' => 0,
      'category' => 4,
      'cache_type' => 0,
      'snippet' => '<?php
$folder_message = "Hello ".$modx->user->get(\'username\')." this is your folder";
$MaxSizeUpload = 100;
$default_language = "en_EN";
$icon_theme = "ico";
$show_folder_size = true;
$show_sorting_bar = true;
$transliteration = false;
$convert_spaces = false;
$replace_with = "_";
$lazy_loading_file_number_threshold = 0;
$image_max_width = 0;
$image_max_height = 0;
$image_max_mode = \'auto\';
$image_resizing = false;
$image_resizing_width = 0;
$image_resizing_height = 0;
$image_resizing_mode = \'auto\';
$image_resizing_override = false;
$default_view = 0;
$ellipsis_title_after_first_row = true;
$delete_files = true;
$create_folders = true;
$delete_folders = true;
$upload_files = true;
$rename_files = true;
$rename_folders = true;
$duplicate_files = true;
$copy_cut_files = true;
$copy_cut_dirs = true;
$chmod_files = false;
$chmod_dirs = false;
$preview_text_files = true;
$edit_text_files = true;
$create_text_files = true;
$previewable_text_file_exts = array(\'txt\', \'log\', \'xml\', \'html\', \'css\', \'htm\', \'js\');
$previewable_text_file_exts_no_prettify = array(\'txt\', \'log\');
$editable_text_file_exts = array(\'txt\', \'log\', \'xml\', \'html\', \'css\', \'htm\', \'js\');
$googledoc_enabled = true;
$googledoc_file_exts = array(\'doc\', \'docx\', \'xls\', \'xlsx\', \'ppt\', \'pptx\');
$viewerjs_enabled = true;
$viewerjs_file_exts = array(\'pdf\', \'odt\', \'odp\', \'ods\');
$copy_cut_max_size = 100;
$copy_cut_max_count = 200;
$ext_img = array(\'jpg\', \'jpeg\', \'png\', \'gif\', \'bmp\', \'tiff\', \'svg\');
$ext_file = array(\'doc\', \'docx\', \'rtf\', \'pdf\', \'xls\', \'xlsx\', \'txt\', \'csv\', \'html\', \'xhtml\', \'psd\', \'sql\', \'log\', \'fla\', \'xml\', \'ade\', \'adp\', \'mdb\', \'accdb\', \'ppt\', \'pptx\', \'odt\', \'ots\', \'ott\', \'odb\', \'odg\', \'otp\', \'otg\', \'odf\', \'ods\', \'odp\', \'css\', \'ai\');
$ext_video = array(\'mov\', \'mpeg\', \'m4v\', \'mp4\', \'avi\', \'mpg\', \'wma\', "flv", "webm");
$ext_music = array(\'mp3\', \'m4a\', \'ac3\', \'aiff\', \'mid\', \'ogg\', \'wav\');
$ext_misc = array(\'zip\', \'rar\', \'gz\', \'tar\', \'iso\', \'dmg\');
$aviary_active = true;
$aviary_apiKey = "dvh8qudbp6yx2bnp";
$aviary_secret = "m6xaym5q42rpw433";
$file_number_limit_js = 500;
$hidden_folders = array();
$hidden_files = array(\'config.php\');
$java_upload = true;
$JAVAMaxSizeUpload = 200;
$fixed_image_creation = false;
// $fixed_path_from_filemanager = array(\'../test/\', \'../test1/\');
// $fixed_image_creation_name_to_prepend = array(\'\', \'test_\');
// $fixed_image_creation_to_append = array(\'_test\', \'\');
// $fixed_image_creation_width = array(300, 400);
// $fixed_image_creation_height = array(200, \'\');
// $fixed_image_creation_option = array(\'crop\', \'auto\');
// $relative_image_creation = false;
// $relative_path_from_current_pos = array(\'thumb/\', \'thumb/\');
// $relative_image_creation_name_to_prepend = array(\'\', \'test_\');
// $relative_image_creation_name_to_append = array(\'_test\', \'\');
// $relative_image_creation_width = array(300, 400);
// $relative_image_creation_height = array(200, \'\');
// $relative_image_creation_option = array(\'crop\', \'crop\');
// $remember_text_filter = false;
?>',
      'locked' => 0,
      'properties' => NULL,
      'static' => 0,
      'static_file' => '',
      'content' => '<?php
$folder_message = "Hello ".$modx->user->get(\'username\')." this is your folder";
$MaxSizeUpload = 100;
$default_language = "en_EN";
$icon_theme = "ico";
$show_folder_size = true;
$show_sorting_bar = true;
$transliteration = false;
$convert_spaces = false;
$replace_with = "_";
$lazy_loading_file_number_threshold = 0;
$image_max_width = 0;
$image_max_height = 0;
$image_max_mode = \'auto\';
$image_resizing = false;
$image_resizing_width = 0;
$image_resizing_height = 0;
$image_resizing_mode = \'auto\';
$image_resizing_override = false;
$default_view = 0;
$ellipsis_title_after_first_row = true;
$delete_files = true;
$create_folders = true;
$delete_folders = true;
$upload_files = true;
$rename_files = true;
$rename_folders = true;
$duplicate_files = true;
$copy_cut_files = true;
$copy_cut_dirs = true;
$chmod_files = false;
$chmod_dirs = false;
$preview_text_files = true;
$edit_text_files = true;
$create_text_files = true;
$previewable_text_file_exts = array(\'txt\', \'log\', \'xml\', \'html\', \'css\', \'htm\', \'js\');
$previewable_text_file_exts_no_prettify = array(\'txt\', \'log\');
$editable_text_file_exts = array(\'txt\', \'log\', \'xml\', \'html\', \'css\', \'htm\', \'js\');
$googledoc_enabled = true;
$googledoc_file_exts = array(\'doc\', \'docx\', \'xls\', \'xlsx\', \'ppt\', \'pptx\');
$viewerjs_enabled = true;
$viewerjs_file_exts = array(\'pdf\', \'odt\', \'odp\', \'ods\');
$copy_cut_max_size = 100;
$copy_cut_max_count = 200;
$ext_img = array(\'jpg\', \'jpeg\', \'png\', \'gif\', \'bmp\', \'tiff\', \'svg\');
$ext_file = array(\'doc\', \'docx\', \'rtf\', \'pdf\', \'xls\', \'xlsx\', \'txt\', \'csv\', \'html\', \'xhtml\', \'psd\', \'sql\', \'log\', \'fla\', \'xml\', \'ade\', \'adp\', \'mdb\', \'accdb\', \'ppt\', \'pptx\', \'odt\', \'ots\', \'ott\', \'odb\', \'odg\', \'otp\', \'otg\', \'odf\', \'ods\', \'odp\', \'css\', \'ai\');
$ext_video = array(\'mov\', \'mpeg\', \'m4v\', \'mp4\', \'avi\', \'mpg\', \'wma\', "flv", "webm");
$ext_music = array(\'mp3\', \'m4a\', \'ac3\', \'aiff\', \'mid\', \'ogg\', \'wav\');
$ext_misc = array(\'zip\', \'rar\', \'gz\', \'tar\', \'iso\', \'dmg\');
$aviary_active = true;
$aviary_apiKey = "dvh8qudbp6yx2bnp";
$aviary_secret = "m6xaym5q42rpw433";
$file_number_limit_js = 500;
$hidden_folders = array();
$hidden_files = array(\'config.php\');
$java_upload = true;
$JAVAMaxSizeUpload = 200;
$fixed_image_creation = false;
// $fixed_path_from_filemanager = array(\'../test/\', \'../test1/\');
// $fixed_image_creation_name_to_prepend = array(\'\', \'test_\');
// $fixed_image_creation_to_append = array(\'_test\', \'\');
// $fixed_image_creation_width = array(300, 400);
// $fixed_image_creation_height = array(200, \'\');
// $fixed_image_creation_option = array(\'crop\', \'auto\');
// $relative_image_creation = false;
// $relative_path_from_current_pos = array(\'thumb/\', \'thumb/\');
// $relative_image_creation_name_to_prepend = array(\'\', \'test_\');
// $relative_image_creation_name_to_append = array(\'_test\', \'\');
// $relative_image_creation_width = array(300, 400);
// $relative_image_creation_height = array(200, \'\');
// $relative_image_creation_option = array(\'crop\', \'crop\');
// $remember_text_filter = false;
?>',
    ),
  ),
  '5dc3698c0b6f231604c7b550854b3221' => 
  array (
    'criteria' => 
    array (
      'name' => 'responsivefilemanagerConnector',
    ),
    'object' => 
    array (
      'id' => 3,
      'source' => 0,
      'property_preprocess' => 1,
      'name' => 'responsivefilemanagerConnector',
      'description' => 'This snippet is called by responsivefilemanager/filemanager/config/dialog.php
Observe the chunks and snippets created by TinymceWrapper.
Duplicate and backup to save you from swearing.',
      'editor_type' => 0,
      'category' => 4,
      'cache_type' => 0,
      'snippet' => 'function listArray($list){
  $list = preg_replace(\'/\\s+/\', \'\', $list);
  // $list = preg_replace("/[^a-z0-9,-_]+/i", \' \', $list);
  $list = preg_replace("/[^a-zA-Z0-9,.]+/", " ", html_entity_decode($list));
  $list = explode(\',\', $list);
  return $list;
}
if ($modx->user->isMember(listArray($rfmUserGroups))) {
} 
else {
  die($noAccessMessage);
}
if ($activatePersonalFolders == 1 && $autoCreateFoldersTWrfmSnippet) {
 $myPersonalFolderName = $modx->runSnippet($autoCreateFoldersTWrfmSnippet, array(\'path\'=>$absolutePATHtoUploadFolder_modx, \'copyConfig\' =>$copyConfig, \'responsivePersonalConfig\' => $responsivePersonalConfig));
 $absoluteURLtoUploadFolder_modx.= $myPersonalFolderName.\'/\';
 $relativePathToUploadFolder_modx.= $myPersonalFolderName.\'/\';
}
mb_internal_encoding(\'UTF-8\');
date_default_timezone_set(\'Europe/Rome\');
define(\'USE_ACCESS_KEYS\', $USE_ACCESS_KEYS_modx);
define(\'DEBUG_ERROR_MESSAGE\', true); //new
$config = array(
  \'base_url\' => ((isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] && !in_array(strtolower($_SERVER[\'HTTPS\']), array(
    \'off\',
    \'no\'
  ))) ? \'https\' : \'http\') . \'://\' . $_SERVER[\'HTTP_HOST\'],
  \'upload_dir\' => $absoluteURLtoUploadFolder_modx,
  \'current_path\' => $relativePathToUploadFolder_modx,
  \'thumbs_base_path\' => $relativePathToUploadFolderThumbs_modx,
  \'access_keys\' => listArray($access_keys_modx),
  \'MaxSizeTotal\' => $MaxSizeTotal_modx, //new
  \'MaxSizeUpload\' => $MaxSizeUpload_modx,
  \'default_language\' => $default_language_modx,
  \'icon_theme\' => $icon_theme_modx,
  \'folder_message\' => $folder_message_modx,
  \'show_total_size\' => $show_total_size_modx, //new
  \'show_folder_size\' => $show_folder_size_modx,
  \'show_sorting_bar\' => $show_sorting_bar_modx,
  \'show_filter_buttons\' => $show_filter_buttons_modx, //new
  \'show_language_selection\' => $show_language_selection_modx, //new
  \'transliteration\' => $transliteration_modx,
  \'convert_spaces\' => $convert_spaces_modx,
  \'replace_with\' => $replace_with_modx,
  \'lower_case\' => $lower_case_modx, //new
  \'lazy_loading_file_number_threshold\' => $lazy_loading_file_number_threshold_modx,
  \'image_max_width\' => $image_max_width_modx,
  \'image_max_height\' => $image_max_height_modx,
  \'image_max_mode\' => $image_max_mode_modx,
  \'image_resizing\' => $image_resizing_modx,
  \'image_resizing_width\' => $image_resizing_width_modx,
  \'image_resizing_height\' => $image_resizing_height_modx,
  \'image_resizing_mode\' => $image_resizing_mode_modx,
  \'image_resizing_override\' => $image_resizing_override_modx,
  \'default_view\' => $default_view_modx,
  \'ellipsis_title_after_first_row\' => $ellipsis_title_after_first_row_modx,
  \'delete_files\' => $delete_files_modx,
  \'create_folders\' => $create_folders_modx,
  \'delete_folders\' => $delete_folders_modx,
  \'upload_files\' => $upload_files_modx,
  \'rename_files\' => $rename_files_modx,
  \'rename_folders\' => $rename_folders_modx,
  \'duplicate_files\' => $duplicate_files_modx,
  \'copy_cut_files\' => $copy_cut_files_modx,
  \'copy_cut_dirs\' => $copy_cut_dirs_modx,
  \'chmod_files\' => $chmod_files_modx,
  \'chmod_dirs\' => $chmod_dirs_modx,
  \'preview_text_files\' => $preview_text_files_modx,
  \'edit_text_files\' => $edit_text_files_modx,
  \'create_text_files\' => $create_text_files_modx,
  \'previewable_text_file_exts\' => listArray($previewable_text_file_exts_modx),
  \'previewable_text_file_exts_no_prettify\' => listArray($previewable_text_file_exts_no_prettify_modx),
  \'editable_text_file_exts\' => listArray($editable_text_file_exts_modx),
  \'googledoc_enabled\' => $googledoc_enabled_modx,
  \'googledoc_file_exts\' => listArray($googledoc_file_exts_modx),
  \'viewerjs_enabled\' => $viewerjs_enabled_modx,
  \'viewerjs_file_exts\' => listArray($viewerjs_file_exts_modx),
  \'copy_cut_max_size\' => $copy_cut_max_size_modx,
  \'copy_cut_max_count\' => $copy_cut_max_count_modx,
  \'ext_img\' => listArray($ext_img_modx),
  \'ext_file\' => listArray($ext_file_modx),
  \'ext_video\' => listArray($ext_video_modx),
  \'ext_music\' => listArray($ext_music_modx),
  \'ext_misc\' => listArray($ext_misc_modx),
  \'aviary_active\' => $aviary_active_modx,
  \'aviary_apiKey\' => $aviary_apiKey_modx,
  \'aviary_language\' => $aviary_language_modx,
  \'aviary_theme\' => $aviary_theme_modx,
  \'aviary_tools\' => $aviary_tools_modx,
  \'aviary_maxSize\' => $aviary_maxSize_modx, //new
  \'file_number_limit_js\' => $file_number_limit_js_modx,
  \'hidden_folders\' => listArray($hidden_folders_modx),
  \'hidden_files\' => listArray($hidden_files_modx),
  \'java_upload\' => $java_upload_modx,
  \'JAVAMaxSizeUpload\' => $JAVAMaxSizeUpload_modx,
  \'fixed_image_creation\' => $fixed_image_creation_modx,
  \'fixed_path_from_filemanager\' => listArray($fixed_path_from_filemanager_modx),
  \'fixed_image_creation_name_to_prepend\' => listArray($fixed_image_creation_name_to_prepend_modx),
  \'fixed_image_creation_to_append\' => listArray($fixed_image_creation_to_append_modx),
  \'fixed_image_creation_width\' => listArray($fixed_image_creation_width_modx),
  \'fixed_image_creation_height\' => listArray($fixed_image_creation_height_modx),
  \'fixed_image_creation_option\' => listArray($fixed_image_creation_option_modx),
  \'relative_image_creation\' => $relative_image_creation_modx,
  \'relative_path_from_current_pos\' => listArray($relative_path_from_current_pos_modx),
  \'relative_image_creation_name_to_prepend\' => listArray($relative_image_creation_name_to_prepend_modx),
  \'relative_image_creation_name_to_append\' => listArray($relative_image_creation_name_to_append_modx),
  \'relative_image_creation_width\' => listArray($relative_image_creation_width_modx),
  \'relative_image_creation_height\' => listArray($relative_image_creation_height_modx),
  \'relative_image_creation_option\' => listArray($relative_image_creation_option_modx),
  \'remember_text_filter\' => $remember_text_filter_modx
);
return array_merge($config, array(
  \'MaxSizeUpload\' => ((int) (ini_get(\'post_max_size\')) < $config[\'MaxSizeUpload\']) ? (int) (ini_get(\'post_max_size\')) : $config[\'MaxSizeUpload\'],
  \'ext\' => array_merge($config[\'ext_img\'], $config[\'ext_file\'], $config[\'ext_misc\'], $config[\'ext_video\'], $config[\'ext_music\']),
  \'aviary_defaults_config\' => array(
    \'apiKey\' => $config[\'aviary_apiKey\'],
    \'language\' => $config[\'aviary_language\'],
    \'theme\' => $config[\'aviary_theme\'],
    \'tools\' => $config[\'aviary_tools\'],
    \'maxSize\' => $config[\'aviary_maxSize\'] //new
  )
));',
      'locked' => 0,
      'properties' => 'a:92:{s:23:"activatePersonalFolders";a:7:{s:4:"name";s:23:"activatePersonalFolders";s:4:"desc";s:257:"auto create any number of folders under users\' personal folder. E.g example.com/john/ will be created .... and example.com/john/images/ and example.com/john/mp3/. Please find RFM snippet to configure. Note...user will not be able to access any folder higher";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:29:"autoCreateFoldersTWrfmSnippet";a:7:{s:4:"name";s:29:"autoCreateFoldersTWrfmSnippet";s:4:"desc";s:234:"If you have activatePersonalFolders and copyConfig set to true, and want to further customize each user\'s folder, this snippet runs all the code necessary. It will also use responsivePersonalConfig chunk template for further security.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:22:"autoCreateFoldersTWrfm";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:10:"copyConfig";a:7:{s:4:"name";s:10:"copyConfig";s:4:"desc";s:147:"if you have activatePersonalFolders set to true, and want to further customize each user\'s folder, auto copy a small config.php file to each folder";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:15:"noAccessMessage";a:7:{s:4:"name";s:15:"noAccessMessage";s:4:"desc";s:0:"";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:220:"<div style="position: fixed; margin: auto;width: 400px;height:200px;text-align:center;top:0;bottom:0;left:0;right:0;"><h1>IT SEEMS YOU DO NOT HAVE PERMISSION TO USE THIS COOL MANAGER</h1>BEG SOMEONE TO PROMOTE YOU.</div>";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:24:"responsivePersonalConfig";a:7:{s:4:"name";s:24:"responsivePersonalConfig";s:4:"desc";s:275:"If you have activatePersonalFolders and copyConfig set to true, and want to further customize each user\'s folder, this chunk template will be transformed into a small config.php file for each user\'s folder. This chunk is dealt with by the autoCreateFoldersTWrfmSnippet option";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:24:"responsivePersonalConfig";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:13:"rfmUserGroups";a:7:{s:4:"name";s:13:"rfmUserGroups";s:4:"desc";s:42:"Comma-separated list of Who can access RFM";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:26:"Administrator, BlogEditors";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:24:"DEBUG_ERROR_MESSAGE_modx";a:7:{s:4:"name";s:24:"DEBUG_ERROR_MESSAGE_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:7:"01 main";}s:20:"USE_ACCESS_KEYS_modx";a:7:{s:4:"name";s:20:"USE_ACCESS_KEYS_modx";s:4:"desc";s:442:"if set to true only those will access RF whose url contains the access key(akey) like:
input type="button" href="../filemanager/dialog.php?field_id=imgField&amp;lang=en_EN&amp;akey=myPrivateKey" value="Files"
| in tinymce a new parameter added: filemanager_access_key:"myPrivateKey" example tinymce config:   
tiny init ...
external_filemanager_path:"../filemanager/",
filemanager_title:"Filemanager" ,
filemanager_access_key:"myPrivateKey" ,";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:7:"01 main";}s:31:"absolutePATHtoUploadFolder_modx";a:7:{s:4:"name";s:31:"absolutePATHtoUploadFolder_modx";s:4:"desc";s:162:"Please provide the corresponding /full/path/to/your/site/assets/upload/folder/ if you have personal folders activated.
In MODx, [[++base_path]] and [[++base_url]]";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:17:"[[++assets_path]]";s:7:"lexicon";N;s:4:"area";s:7:"01 main";}s:30:"absoluteURLtoUploadFolder_modx";a:7:{s:4:"name";s:30:"absoluteURLtoUploadFolder_modx";s:4:"desc";s:96:"path from base_url to base of upload folder with start and final / e.g /path/to/your/site/assets";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:16:"[[++assets_url]]";s:7:"lexicon";N;s:4:"area";s:7:"01 main";}s:16:"access_keys_modx";a:7:{s:4:"name";s:16:"access_keys_modx";s:4:"desc";s:406:"Comma-separated: add access keys eg: array("myPrivateKey", "someoneElseKey");
 keys should only containt (a-z A-Z 0-9 \\ . _ -) characters
 if you are integrating lets say to a cms for admins, i recommend making keys randomized something like this:
 $username = "Admin";
 $salt = "dsflFWR9u2xQa" (a hard coded string)
 $akey = md5($username.$salt);
 DO NOT use "key" as access key!
 Keys are CASE SENSITIVE!";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:22:"hardPassword, toughGuy";s:7:"lexicon";N;s:4:"area";s:7:"01 main";}s:37:"relativePathToUploadFolderThumbs_modx";a:7:{s:4:"name";s:37:"relativePathToUploadFolderThumbs_modx";s:4:"desc";s:31:"DO NOT put inside upload folder";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:19:"../../../../thumbs/";s:7:"lexicon";N;s:4:"area";s:7:"01 main";}s:31:"relativePathToUploadFolder_modx";a:7:{s:4:"name";s:31:"relativePathToUploadFolder_modx";s:4:"desc";s:221:"relative path from filemanager folder to upload folder with final / . Remember Responsive FileManager is FIVE folders away from your base/root folder. You need FIVE SLASHES to get there, and FOUR SLASHES to get to assets.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"../../../../";s:7:"lexicon";N;s:4:"area";s:7:"01 main";}s:17:"MaxSizeTotal_modx";a:7:{s:4:"name";s:17:"MaxSizeTotal_modx";s:4:"desc";s:57:"Maximum size of all files in source folder (in Megabytes)";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:18:"MaxSizeUpload_modx";a:7:{s:4:"name";s:18:"MaxSizeUpload_modx";s:4:"desc";s:34:"Maximum upload size - in Megabytes";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:3:"100";s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:19:"convert_spaces_modx";a:7:{s:4:"name";s:19:"convert_spaces_modx";s:4:"desc";s:77:"convert all spaces on files name and folders name with $replace_with variable";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:21:"default_language_modx";a:7:{s:4:"name";s:21:"default_language_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"en_EN";s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:19:"folder_message_modx";a:7:{s:4:"name";s:19:"folder_message_modx";s:4:"desc";s:0:"";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:47:"Hi [[+modx.user.username]], welcome to the zone";s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:15:"icon_theme_modx";a:7:{s:4:"name";s:15:"icon_theme_modx";s:4:"desc";s:48:"Can be set to custom icon inside filemanager/img";s:4:"type";s:4:"list";s:7:"options";a:2:{i:0;a:2:{s:4:"text";s:3:"ico";s:5:"value";s:3:"ico";}i:1;a:2:{s:4:"text";s:8:"ico_dark";s:5:"value";s:8:"ico_dark";}}s:5:"value";s:3:"ico";s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:39:"lazy_loading_file_number_threshold_modx";a:7:{s:4:"name";s:39:"lazy_loading_file_number_threshold_modx";s:4:"desc";s:155:"-1: There is no lazy loading at all, 0: Always lazy-load images, 0+: The minimum number of the files in a directory
 when lazy loading should be turned on.";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:15:"lower_case_modx";a:7:{s:4:"name";s:15:"lower_case_modx";s:4:"desc";s:47:"convert to lowercase the files and folders name";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:17:"replace_with_modx";a:7:{s:4:"name";s:17:"replace_with_modx";s:4:"desc";s:61:"convert all spaces on files name and folders name this value
";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:1:"_";s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:24:"show_filter_buttons_modx";a:7:{s:4:"name";s:24:"show_filter_buttons_modx";s:4:"desc";s:46:"Show or not show filters button in filemanager";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:21:"show_folder_size_modx";a:7:{s:4:"name";s:21:"show_folder_size_modx";s:4:"desc";s:145:"Show or not show folder size in list view feature in filemanager (is possible, if there is a large folder, to greatly increase the calculations)
";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:28:"show_language_selection_modx";a:7:{s:4:"name";s:28:"show_language_selection_modx";s:4:"desc";s:53:"Show or not language selection feature in filemanager";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:5:"false";s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:21:"show_sorting_bar_modx";a:7:{s:4:"name";s:21:"show_sorting_bar_modx";s:4:"desc";s:48:"Show or not show sorting feature in filemanager
";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:20:"show_total_size_modx";a:7:{s:4:"name";s:20:"show_total_size_modx";s:4:"desc";s:88:"Show or not total size in filemanager (is possible to greatly increase the calculations)";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:20:"transliteration_modx";a:7:{s:4:"name";s:20:"transliteration_modx";s:4:"desc";s:104:"active or deactive the transliteration (mean convert all strange characters in A..Za..z0..9 characters)
";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"02 main";}s:21:"image_max_height_modx";a:7:{s:4:"name";s:21:"image_max_height_modx";s:4:"desc";s:242:"set maximum pixel width and/or maximum pixel height for all images. If you set a maximum width or height, oversized images are converted to those limits. Images smaller than the limit(s) are unaffected, if you don"t need a limit set both to 0";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";N;s:4:"area";s:27:"03 image limit and resizing";}s:19:"image_max_mode_modx";a:7:{s:4:"name";s:19:"image_max_mode_modx";s:4:"desc";s:149:"exact = defined size;    
portrait = keep aspect set height;      
landscape = keep aspect set width;     
auto = auto;       
crop= resize and crop;";s:4:"type";s:4:"list";s:7:"options";a:5:{i:0;a:2:{s:4:"text";s:4:"auto";s:5:"value";s:4:"auto";}i:1;a:2:{s:4:"text";s:5:"exact";s:5:"value";s:5:"exact";}i:2;a:2:{s:4:"text";s:8:"portrait";s:5:"value";s:8:"portrait";}i:3;a:2:{s:4:"text";s:9:"landscape";s:5:"value";s:9:"landscape";}i:4;a:2:{s:4:"text";s:4:"crop";s:5:"value";s:4:"crop";}}s:5:"value";s:4:"auto";s:7:"lexicon";N;s:4:"area";s:27:"03 image limit and resizing";}s:20:"image_max_width_modx";a:7:{s:4:"name";s:20:"image_max_width_modx";s:4:"desc";s:242:"set maximum pixel width and/or maximum pixel height for all images. If you set a maximum width or height, oversized images are converted to those limits. Images smaller than the limit(s) are unaffected, if you don"t need a limit set both to 0";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";N;s:4:"area";s:27:"03 image limit and resizing";}s:26:"image_resizing_height_modx";a:7:{s:4:"name";s:26:"image_resizing_height_modx";s:4:"desc";s:386:"If you set image_resizing to TRUE the script converts all uploaded images exactly to image_resizing_width x image_resizing_height dimension.         
If you set width or height to 0 the script automatically calculates the other dimension         
Is possible that if you upload very big images the script not work to overcome this increase the php configuration of memory and time limit";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";N;s:4:"area";s:27:"04 image automatic resizing";}s:24:"image_resizing_mode_modx";a:7:{s:4:"name";s:24:"image_resizing_mode_modx";s:4:"desc";s:22:"same as image_max_mode";s:4:"type";s:4:"list";s:7:"options";a:5:{i:0;a:2:{s:4:"text";s:4:"auto";s:5:"value";s:4:"auto";}i:1;a:2:{s:4:"text";s:5:"exact";s:5:"value";s:5:"exact";}i:2;a:2:{s:4:"text";s:9:"landscape";s:5:"value";s:9:"landscape";}i:3;a:2:{s:4:"text";s:8:"portrait";s:5:"value";s:8:"portrait";}i:4;a:2:{s:4:"text";s:4:"crop";s:5:"value";s:4:"crop";}}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:27:"04 image automatic resizing";}s:19:"image_resizing_modx";a:7:{s:4:"name";s:19:"image_resizing_modx";s:4:"desc";s:386:"If you set image_resizing to TRUE the script converts all uploaded images exactly to image_resizing_width x image_resizing_height dimension.         
If you set width or height to 0 the script automatically calculates the other dimension         
Is possible that if you upload very big images the script not work to overcome this increase the php configuration of memory and time limit";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:27:"04 image automatic resizing";}s:28:"image_resizing_override_modx";a:7:{s:4:"name";s:28:"image_resizing_override_modx";s:4:"desc";s:195:"If set to TRUE then you can specify bigger images than image_max_width &amp; height otherwise if image_resizing is
bigger than $image_max_width or height then it will be converted to those values";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:27:"04 image automatic resizing";}s:25:"image_resizing_width_modx";a:7:{s:4:"name";s:25:"image_resizing_width_modx";s:4:"desc";s:386:"If you set image_resizing to TRUE the script converts all uploaded images exactly to image_resizing_width x image_resizing_height dimension.         
If you set width or height to 0 the script automatically calculates the other dimension         
Is possible that if you upload very big images the script not work to overcome this increase the php configuration of memory and time limit";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";N;s:4:"area";s:27:"04 image automatic resizing";}s:17:"default_view_modx";a:7:{s:4:"name";s:17:"default_view_modx";s:4:"desc";s:192:"0 => boxes
1 => detailed list (1 column)
2 => columns list (multiple columns depending on the width of the page)
YOU CAN ALSO PASS THIS PARAMETERS USING SESSION VAR => $_SESSION["RF"]["VIEW"]=";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:0;s:7:"lexicon";N;s:4:"area";s:9:"05 layout";}s:35:"ellipsis_title_after_first_row_modx";a:7:{s:4:"name";s:35:"ellipsis_title_after_first_row_modx";s:4:"desc";s:57:"set if the filename is truncated when overflow first row
";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:9:"05 layout";}s:15:"chmod_dirs_modx";a:7:{s:4:"name";s:15:"chmod_dirs_modx";s:4:"desc";s:25:"change folder permissions";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:16:"chmod_files_modx";a:7:{s:4:"name";s:16:"chmod_files_modx";s:4:"desc";s:23:"change file permissions";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:18:"copy_cut_dirs_modx";a:7:{s:4:"name";s:18:"copy_cut_dirs_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:19:"copy_cut_files_modx";a:7:{s:4:"name";s:19:"copy_cut_files_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:19:"create_folders_modx";a:7:{s:4:"name";s:19:"create_folders_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:22:"create_text_files_modx";a:7:{s:4:"name";s:22:"create_text_files_modx";s:4:"desc";s:63:"only create files with exts. defined in editable_text_file_exts";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:17:"delete_files_modx";a:7:{s:4:"name";s:17:"delete_files_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:19:"delete_folders_modx";a:7:{s:4:"name";s:19:"delete_folders_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:20:"duplicate_files_modx";a:7:{s:4:"name";s:20:"duplicate_files_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:20:"edit_text_files_modx";a:7:{s:4:"name";s:20:"edit_text_files_modx";s:4:"desc";s:18:"eg.: txt, log etc.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:23:"preview_text_files_modx";a:7:{s:4:"name";s:23:"preview_text_files_modx";s:4:"desc";s:18:"eg.: txt, log etc.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:17:"rename_files_modx";a:7:{s:4:"name";s:17:"rename_files_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:19:"rename_folders_modx";a:7:{s:4:"name";s:19:"rename_folders_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:17:"upload_files_modx";a:7:{s:4:"name";s:17:"upload_files_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:21:"06 permissions config";}s:28:"editable_text_file_exts_modx";a:7:{s:4:"name";s:28:"editable_text_file_exts_modx";s:4:"desc";s:269:"you can edit these type of files if $edit_text_files is true (only text based files)    
you can create these type of files if $create_text_files is true (only text based files)    
if you want you can add html,css etc.   
but for security reasons it"s NOT RECOMMENDED!";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:33:"txt, log, xml, html, css, htm, js";s:7:"lexicon";N;s:4:"area";s:42:"07 permission - previewables and editables";}s:22:"googledoc_enabled_modx";a:7:{s:4:"name";s:22:"googledoc_enabled_modx";s:4:"desc";s:29:"Preview with Google Documents";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:42:"07 permission - previewables and editables";}s:24:"googledoc_file_exts_modx";a:7:{s:4:"name";s:24:"googledoc_file_exts_modx";s:4:"desc";s:65:"you can preview these type of files if preview_text_files is true";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:33:"txt, log, xml, html, css, htm, js";s:7:"lexicon";N;s:4:"area";s:42:"07 permission - previewables and editables";}s:31:"previewable_text_file_exts_modx";a:7:{s:4:"name";s:31:"previewable_text_file_exts_modx";s:4:"desc";s:65:"you can preview these type of files if preview_text_files is true";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:33:"txt, log, xml, html, css, htm, js";s:7:"lexicon";N;s:4:"area";s:42:"07 permission - previewables and editables";}s:43:"previewable_text_file_exts_no_prettify_modx";a:7:{s:4:"name";s:43:"previewable_text_file_exts_no_prettify_modx";s:4:"desc";s:65:"you can preview these type of files if preview_text_files is true";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:8:"txt, log";s:7:"lexicon";N;s:4:"area";s:42:"07 permission - previewables and editables";}s:21:"viewerjs_enabled_modx";a:7:{s:4:"name";s:21:"viewerjs_enabled_modx";s:4:"desc";s:22:"Preview with Viewer.js";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:42:"07 permission - previewables and editables";}s:23:"viewerjs_file_exts_modx";a:7:{s:4:"name";s:23:"viewerjs_file_exts_modx";s:4:"desc";s:22:"Preview with Viewer.js";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:18:"pdf, odt, odp, ods";s:7:"lexicon";N;s:4:"area";s:42:"07 permission - previewables and editables";}s:23:"copy_cut_max_count_modx";a:7:{s:4:"name";s:23:"copy_cut_max_count_modx";s:4:"desc";s:149:"defines file count limit for paste / operation
set "FALSE" for no limit   
IF any of these limits reached, operation won"t start and generate warning";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:3:"200";s:7:"lexicon";N;s:4:"area";s:30:"08 permission - copy/cut/paste";}s:22:"copy_cut_max_size_modx";a:7:{s:4:"name";s:22:"copy_cut_max_size_modx";s:4:"desc";s:152:"defines size limit for paste in MB / operation   
set "FALSE" for no limit   
IF any of these limits reached, operation won"t start and generate warning";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:3:"100";s:7:"lexicon";N;s:4:"area";s:30:"08 permission - copy/cut/paste";}s:13:"ext_file_modx";a:7:{s:4:"name";s:13:"ext_file_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:170:"doc, docx, rtf, pdf, xls, xlsx, txt, csv, html, xhtml, psd, sql, log, fla, xml, ade, adp, mdb, accdb, ppt, pptx, odt, ots, ott, odb, odg, otp, otg, odf, ods, odp, css, ai";s:7:"lexicon";N;s:4:"area";s:34:"09 permission - allowed extensions";}s:12:"ext_img_modx";a:7:{s:4:"name";s:12:"ext_img_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:35:"jpg, jpeg, png, gif, bmp, tiff, svg";s:7:"lexicon";N;s:4:"area";s:34:"09 permission - allowed extensions";}s:13:"ext_misc_modx";a:7:{s:4:"name";s:13:"ext_misc_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:27:"zip, rar, gz, tar, iso, dmg";s:7:"lexicon";N;s:4:"area";s:34:"09 permission - allowed extensions";}s:14:"ext_music_modx";a:7:{s:4:"name";s:14:"ext_music_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:34:"mp3, m4a, ac3, aiff, mid, ogg, wav";s:7:"lexicon";N;s:4:"area";s:34:"09 permission - allowed extensions";}s:14:"ext_video_modx";a:7:{s:4:"name";s:14:"ext_video_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:45:"mov, mpeg, m4v, mp4, avi, mpg, wma, flv, webm";s:7:"lexicon";N;s:4:"area";s:34:"09 permission - allowed extensions";}s:18:"aviary_active_modx";a:7:{s:4:"name";s:18:"aviary_active_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:28:"10 Aviary photo editing tool";}s:18:"aviary_apiKey_modx";a:7:{s:4:"name";s:18:"aviary_apiKey_modx";s:4:"desc";s:57:"Use this API if you want 2444282ef4344e3dacdedc7a78f8877d";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:32:"2444282ef4344e3dacdedc7a78f8877d";s:7:"lexicon";N;s:4:"area";s:28:"10 Aviary photo editing tool";}s:20:"aviary_language_modx";a:7:{s:4:"name";s:20:"aviary_language_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:2:"en";s:7:"lexicon";N;s:4:"area";s:28:"10 Aviary photo editing tool";}s:19:"aviary_maxSize_modx";a:7:{s:4:"name";s:19:"aviary_maxSize_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:4:"1400";s:7:"lexicon";N;s:4:"area";s:28:"10 Aviary photo editing tool";}s:17:"aviary_theme_modx";a:7:{s:4:"name";s:17:"aviary_theme_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"light";s:7:"lexicon";N;s:4:"area";s:28:"10 Aviary photo editing tool";}s:17:"aviary_tools_modx";a:7:{s:4:"name";s:17:"aviary_tools_modx";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:3:"all";s:7:"lexicon";N;s:4:"area";s:28:"10 Aviary photo editing tool";}s:25:"file_number_limit_js_modx";a:7:{s:4:"name";s:25:"file_number_limit_js_modx";s:4:"desc";s:321:"The filter and sorter are managed through both javascript and php scripts because if you have a lot of
file in a folder the javascript script can"t sort all or filter all, so the filemanager switch to php script.
The plugin automatic swich javascript to php when the current folder exceeds the below limit of files number";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:3:"500";s:7:"lexicon";N;s:4:"area";s:26:"11 PHP and JS upload limit";}s:17:"hidden_files_modx";a:7:{s:4:"name";s:17:"hidden_files_modx";s:4:"desc";s:139:"set the names of any files you want hidden. Remember these names will be hidden in all folders (eg "this_document.pdf", "that_image.jpg" )
";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:10:"config.php";s:7:"lexicon";N;s:4:"area";s:39:"12 permission - hidden folder and files";}s:19:"hidden_folders_modx";a:7:{s:4:"name";s:19:"hidden_folders_modx";s:4:"desc";s:198:"set the names of any folders you want hidden (eg "hidden_folder1", "hidden_folder2" ) Remember all folders with these names will be hidden (you can set any exceptions in config.php files on folders)";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:23:".tmb,thumbs,.quarantine";s:7:"lexicon";N;s:4:"area";s:39:"12 permission - hidden folder and files";}s:22:"JAVAMaxSizeUpload_modx";a:7:{s:4:"name";s:22:"JAVAMaxSizeUpload_modx";s:4:"desc";s:5:"in Gb";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:3:"200";s:7:"lexicon";N;s:4:"area";s:14:"13 JAVA upload";}s:16:"java_upload_modx";a:7:{s:4:"name";s:16:"java_upload_modx";s:4:"desc";s:0:"";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:14:"13 JAVA upload";}s:32:"fixed_image_creation_height_modx";a:7:{s:4:"name";s:32:"fixed_image_creation_height_modx";s:4:"desc";s:54:"height of image (you can leave empty if you set width)";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"200, ";s:7:"lexicon";N;s:4:"area";s:24:"14 auto thumbnail -fixed";}s:25:"fixed_image_creation_modx";a:7:{s:4:"name";s:25:"fixed_image_creation_modx";s:4:"desc";s:551:"New image resized creation with fixed path from filemanager folder after uploading (thumbnails in fixed mode)
If you want create images resized out of upload folder for use with external script you can choose this method,
You can create also more than one image at a time just simply add a value in the array
Remember that the image creation respect the folder hierarchy so if you are inside source/test/test1/ the new image will create at
path_from_filemanager/test/test1/
PS if there isn"t write permission in your destination folder you must set it";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:24:"14 auto thumbnail -fixed";}s:41:"fixed_image_creation_name_to_prepend_modx";a:7:{s:4:"name";s:41:"fixed_image_creation_name_to_prepend_modx";s:4:"desc";s:27:"name to prepend on filename";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:7:", test_";s:7:"lexicon";N;s:4:"area";s:24:"14 auto thumbnail -fixed";}s:32:"fixed_image_creation_option_modx";a:7:{s:4:"name";s:32:"fixed_image_creation_option_modx";s:4:"desc";s:156:"set the type of the crop----
exact = defined size;
portrait = keep aspect set height;
landscape = keep aspect set width;
auto = auto;
crop= resize and crop;";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:11:"crop, auto ";s:7:"lexicon";N;s:4:"area";s:24:"14 auto thumbnail -fixed";}s:35:"fixed_image_creation_to_append_modx";a:7:{s:4:"name";s:35:"fixed_image_creation_to_append_modx";s:4:"desc";s:26:"name to append on filename";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:7:"_test, ";s:7:"lexicon";N;s:4:"area";s:24:"14 auto thumbnail -fixed";}s:31:"fixed_image_creation_width_modx";a:7:{s:4:"name";s:31:"fixed_image_creation_width_modx";s:4:"desc";s:54:"width of image (you can leave empty if you set height)";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:8:"300, 400";s:7:"lexicon";N;s:4:"area";s:24:"14 auto thumbnail -fixed";}s:32:"fixed_path_from_filemanager_modx";a:7:{s:4:"name";s:32:"fixed_path_from_filemanager_modx";s:4:"desc";s:73:"fixed path of the image folder from the current position on upload folder";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:19:"../test/, ../test1/";s:7:"lexicon";N;s:4:"area";s:24:"14 auto thumbnail -fixed";}s:35:"relative_image_creation_height_modx";a:7:{s:4:"name";s:35:"relative_image_creation_height_modx";s:4:"desc";s:54:"height of image (you can leave empty if you set width)";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:7:"200, \'\'";s:7:"lexicon";N;s:4:"area";s:27:"15 auto thumbnail -relative";}s:28:"relative_image_creation_modx";a:7:{s:4:"name";s:28:"relative_image_creation_modx";s:4:"desc";s:404:"New image resized creation with relative path inside to upload folder after uploading (thumbnails in relative mode)
With Responsive filemanager you can create automatically resized image inside the upload folder, also more than one at a time
just simply add a value in the array
The image creation path is always relative so if i"m inside source/test/test1 and I upload an image, the path start from here";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:27:"15 auto thumbnail -relative";}s:43:"relative_image_creation_name_to_append_modx";a:7:{s:4:"name";s:43:"relative_image_creation_name_to_append_modx";s:4:"desc";s:26:"name to append on filename";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:15:"_thumb, _thumb1";s:7:"lexicon";N;s:4:"area";s:27:"15 auto thumbnail -relative";}s:44:"relative_image_creation_name_to_prepend_modx";a:7:{s:4:"name";s:44:"relative_image_creation_name_to_prepend_modx";s:4:"desc";s:27:"name to prepend on filename";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:1:",";s:7:"lexicon";N;s:4:"area";s:27:"15 auto thumbnail -relative";}s:35:"relative_image_creation_option_modx";a:7:{s:4:"name";s:35:"relative_image_creation_option_modx";s:4:"desc";s:154:"set the type of the crop  
exact = defined size;
portrait = keep aspect set height;
landscape = keep aspect set width;
auto = auto;
crop= resize and crop;";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:11:"crop, crop ";s:7:"lexicon";N;s:4:"area";s:27:"15 auto thumbnail -relative";}s:34:"relative_image_creation_width_modx";a:7:{s:4:"name";s:34:"relative_image_creation_width_modx";s:4:"desc";s:54:"width of image (you can leave empty if you set height)";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:8:"300, 400";s:7:"lexicon";N;s:4:"area";s:27:"15 auto thumbnail -relative";}s:35:"relative_path_from_current_pos_modx";a:7:{s:4:"name";s:35:"relative_path_from_current_pos_modx";s:4:"desc";s:76:"relative path of the image folder from the current position on upload folder";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"./, ./";s:7:"lexicon";N;s:4:"area";s:27:"15 auto thumbnail -relative";}s:25:"remember_text_filter_modx";a:7:{s:4:"name";s:25:"remember_text_filter_modx";s:4:"desc";s:64:"Remember text filter after close filemanager for future session
";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:6:"2 main";}}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => 'function listArray($list){
  $list = preg_replace(\'/\\s+/\', \'\', $list);
  // $list = preg_replace("/[^a-z0-9,-_]+/i", \' \', $list);
  $list = preg_replace("/[^a-zA-Z0-9,.]+/", " ", html_entity_decode($list));
  $list = explode(\',\', $list);
  return $list;
}
if ($modx->user->isMember(listArray($rfmUserGroups))) {
} 
else {
  die($noAccessMessage);
}
if ($activatePersonalFolders == 1 && $autoCreateFoldersTWrfmSnippet) {
 $myPersonalFolderName = $modx->runSnippet($autoCreateFoldersTWrfmSnippet, array(\'path\'=>$absolutePATHtoUploadFolder_modx, \'copyConfig\' =>$copyConfig, \'responsivePersonalConfig\' => $responsivePersonalConfig));
 $absoluteURLtoUploadFolder_modx.= $myPersonalFolderName.\'/\';
 $relativePathToUploadFolder_modx.= $myPersonalFolderName.\'/\';
}
mb_internal_encoding(\'UTF-8\');
date_default_timezone_set(\'Europe/Rome\');
define(\'USE_ACCESS_KEYS\', $USE_ACCESS_KEYS_modx);
define(\'DEBUG_ERROR_MESSAGE\', true); //new
$config = array(
  \'base_url\' => ((isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] && !in_array(strtolower($_SERVER[\'HTTPS\']), array(
    \'off\',
    \'no\'
  ))) ? \'https\' : \'http\') . \'://\' . $_SERVER[\'HTTP_HOST\'],
  \'upload_dir\' => $absoluteURLtoUploadFolder_modx,
  \'current_path\' => $relativePathToUploadFolder_modx,
  \'thumbs_base_path\' => $relativePathToUploadFolderThumbs_modx,
  \'access_keys\' => listArray($access_keys_modx),
  \'MaxSizeTotal\' => $MaxSizeTotal_modx, //new
  \'MaxSizeUpload\' => $MaxSizeUpload_modx,
  \'default_language\' => $default_language_modx,
  \'icon_theme\' => $icon_theme_modx,
  \'folder_message\' => $folder_message_modx,
  \'show_total_size\' => $show_total_size_modx, //new
  \'show_folder_size\' => $show_folder_size_modx,
  \'show_sorting_bar\' => $show_sorting_bar_modx,
  \'show_filter_buttons\' => $show_filter_buttons_modx, //new
  \'show_language_selection\' => $show_language_selection_modx, //new
  \'transliteration\' => $transliteration_modx,
  \'convert_spaces\' => $convert_spaces_modx,
  \'replace_with\' => $replace_with_modx,
  \'lower_case\' => $lower_case_modx, //new
  \'lazy_loading_file_number_threshold\' => $lazy_loading_file_number_threshold_modx,
  \'image_max_width\' => $image_max_width_modx,
  \'image_max_height\' => $image_max_height_modx,
  \'image_max_mode\' => $image_max_mode_modx,
  \'image_resizing\' => $image_resizing_modx,
  \'image_resizing_width\' => $image_resizing_width_modx,
  \'image_resizing_height\' => $image_resizing_height_modx,
  \'image_resizing_mode\' => $image_resizing_mode_modx,
  \'image_resizing_override\' => $image_resizing_override_modx,
  \'default_view\' => $default_view_modx,
  \'ellipsis_title_after_first_row\' => $ellipsis_title_after_first_row_modx,
  \'delete_files\' => $delete_files_modx,
  \'create_folders\' => $create_folders_modx,
  \'delete_folders\' => $delete_folders_modx,
  \'upload_files\' => $upload_files_modx,
  \'rename_files\' => $rename_files_modx,
  \'rename_folders\' => $rename_folders_modx,
  \'duplicate_files\' => $duplicate_files_modx,
  \'copy_cut_files\' => $copy_cut_files_modx,
  \'copy_cut_dirs\' => $copy_cut_dirs_modx,
  \'chmod_files\' => $chmod_files_modx,
  \'chmod_dirs\' => $chmod_dirs_modx,
  \'preview_text_files\' => $preview_text_files_modx,
  \'edit_text_files\' => $edit_text_files_modx,
  \'create_text_files\' => $create_text_files_modx,
  \'previewable_text_file_exts\' => listArray($previewable_text_file_exts_modx),
  \'previewable_text_file_exts_no_prettify\' => listArray($previewable_text_file_exts_no_prettify_modx),
  \'editable_text_file_exts\' => listArray($editable_text_file_exts_modx),
  \'googledoc_enabled\' => $googledoc_enabled_modx,
  \'googledoc_file_exts\' => listArray($googledoc_file_exts_modx),
  \'viewerjs_enabled\' => $viewerjs_enabled_modx,
  \'viewerjs_file_exts\' => listArray($viewerjs_file_exts_modx),
  \'copy_cut_max_size\' => $copy_cut_max_size_modx,
  \'copy_cut_max_count\' => $copy_cut_max_count_modx,
  \'ext_img\' => listArray($ext_img_modx),
  \'ext_file\' => listArray($ext_file_modx),
  \'ext_video\' => listArray($ext_video_modx),
  \'ext_music\' => listArray($ext_music_modx),
  \'ext_misc\' => listArray($ext_misc_modx),
  \'aviary_active\' => $aviary_active_modx,
  \'aviary_apiKey\' => $aviary_apiKey_modx,
  \'aviary_language\' => $aviary_language_modx,
  \'aviary_theme\' => $aviary_theme_modx,
  \'aviary_tools\' => $aviary_tools_modx,
  \'aviary_maxSize\' => $aviary_maxSize_modx, //new
  \'file_number_limit_js\' => $file_number_limit_js_modx,
  \'hidden_folders\' => listArray($hidden_folders_modx),
  \'hidden_files\' => listArray($hidden_files_modx),
  \'java_upload\' => $java_upload_modx,
  \'JAVAMaxSizeUpload\' => $JAVAMaxSizeUpload_modx,
  \'fixed_image_creation\' => $fixed_image_creation_modx,
  \'fixed_path_from_filemanager\' => listArray($fixed_path_from_filemanager_modx),
  \'fixed_image_creation_name_to_prepend\' => listArray($fixed_image_creation_name_to_prepend_modx),
  \'fixed_image_creation_to_append\' => listArray($fixed_image_creation_to_append_modx),
  \'fixed_image_creation_width\' => listArray($fixed_image_creation_width_modx),
  \'fixed_image_creation_height\' => listArray($fixed_image_creation_height_modx),
  \'fixed_image_creation_option\' => listArray($fixed_image_creation_option_modx),
  \'relative_image_creation\' => $relative_image_creation_modx,
  \'relative_path_from_current_pos\' => listArray($relative_path_from_current_pos_modx),
  \'relative_image_creation_name_to_prepend\' => listArray($relative_image_creation_name_to_prepend_modx),
  \'relative_image_creation_name_to_append\' => listArray($relative_image_creation_name_to_append_modx),
  \'relative_image_creation_width\' => listArray($relative_image_creation_width_modx),
  \'relative_image_creation_height\' => listArray($relative_image_creation_height_modx),
  \'relative_image_creation_option\' => listArray($relative_image_creation_option_modx),
  \'remember_text_filter\' => $remember_text_filter_modx
);
return array_merge($config, array(
  \'MaxSizeUpload\' => ((int) (ini_get(\'post_max_size\')) < $config[\'MaxSizeUpload\']) ? (int) (ini_get(\'post_max_size\')) : $config[\'MaxSizeUpload\'],
  \'ext\' => array_merge($config[\'ext_img\'], $config[\'ext_file\'], $config[\'ext_misc\'], $config[\'ext_video\'], $config[\'ext_music\']),
  \'aviary_defaults_config\' => array(
    \'apiKey\' => $config[\'aviary_apiKey\'],
    \'language\' => $config[\'aviary_language\'],
    \'theme\' => $config[\'aviary_theme\'],
    \'tools\' => $config[\'aviary_tools\'],
    \'maxSize\' => $config[\'aviary_maxSize\'] //new
  )
));',
    ),
  ),
  'c916c126b2865b926cd78cddc20de70b' => 
  array (
    'criteria' => 
    array (
      'name' => 'autoCreateFoldersTWrfm',
    ),
    'object' => 
    array (
      'id' => 4,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'autoCreateFoldersTWrfm',
      'description' => 'This snippet auto creates folders for each user',
      'editor_type' => 0,
      'category' => 4,
      'cache_type' => 0,
      'snippet' => '//if you wish to encrypt this name and/or use another userfield, do it here
$myPersonal= $modx->user->get(\'username\');
/////////////////////////////////////////////

//$path is supplied by the calling connector, elFinder or responsivefilemanager
if (!file_exists($path.$myPersonal.\'/\')) {
    mkdir($path.$myPersonal.\'/\', 0755, true);
}
//at this time, you may create as many subfolders as you want under $myPersonal
if (!file_exists($path.$myPersonal.\'/media/\')) {
    mkdir($path.$myPersonal.\'/media/\', 0755, true);
}

$copyPath = $path.$myPersonal.\'/\';
$targetFile = $copyPath.\'config.php\';
$userSpecificConfigTemplate = $modx->getChunk(\'responsivePersonalConfig\');

//copy user-specific config.php to each folder
if ($copyConfig == 1 && $userSpecificConfigTemplate) {
 //  if (!file_exists($targetFile)) { //kill this line if you want to create this config file each time RFM fires.
     $myfile = fopen($targetFile, "w") or die("Unable to open file!");
     fwrite($myfile, $userSpecificConfigTemplate);
     fclose($myfile);
     //} //kill this line if you want to create this config file each time RFM fires.

}

return $myPersonal;',
      'locked' => 0,
      'properties' => NULL,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '//if you wish to encrypt this name and/or use another userfield, do it here
$myPersonal= $modx->user->get(\'username\');
/////////////////////////////////////////////

//$path is supplied by the calling connector, elFinder or responsivefilemanager
if (!file_exists($path.$myPersonal.\'/\')) {
    mkdir($path.$myPersonal.\'/\', 0755, true);
}
//at this time, you may create as many subfolders as you want under $myPersonal
if (!file_exists($path.$myPersonal.\'/media/\')) {
    mkdir($path.$myPersonal.\'/media/\', 0755, true);
}

$copyPath = $path.$myPersonal.\'/\';
$targetFile = $copyPath.\'config.php\';
$userSpecificConfigTemplate = $modx->getChunk(\'responsivePersonalConfig\');

//copy user-specific config.php to each folder
if ($copyConfig == 1 && $userSpecificConfigTemplate) {
 //  if (!file_exists($targetFile)) { //kill this line if you want to create this config file each time RFM fires.
     $myfile = fopen($targetFile, "w") or die("Unable to open file!");
     fwrite($myfile, $userSpecificConfigTemplate);
     fclose($myfile);
     //} //kill this line if you want to create this config file each time RFM fires.

}

return $myPersonal;',
    ),
  ),
  '924ee72015c8ed619d78ae0e9a4641e0' => 
  array (
    'criteria' => 
    array (
      'category' => 'RoxyFileman',
    ),
    'object' => 
    array (
      'id' => 5,
      'parent' => 2,
      'category' => 'RoxyFileman',
      'rank' => 0,
    ),
  ),
  'fb622e271e184cef5b46a1a0c80d8b91' => 
  array (
    'criteria' => 
    array (
      'name' => 'roxyConfJson',
    ),
    'object' => 
    array (
      'id' => 8,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'roxyConfJson',
      'description' => 'This is the only config file for Roxy Fileman. Enjoy. The errors are still being work out.',
      'editor_type' => 0,
      'category' => 5,
      'cache_type' => 0,
      'snippet' => '{
  "MODX": "initialize",
  "FILES_ROOT": "",
  "RETURN_URL_PREFIX": "",
  "SESSION_PATH_KEY": "",
  "THUMBS_VIEW_WIDTH": "140",
  "THUMBS_VIEW_HEIGHT": "120",
  "PREVIEW_THUMB_WIDTH": "100",
  "PREVIEW_THUMB_HEIGHT": "100",
  "MAX_IMAGE_WIDTH": "1000",
  "MAX_IMAGE_HEIGHT": "1000",
  "INTEGRATION": "tinymce4",
  "DIRLIST": "php/dirtree.php",
  "CREATEDIR": "php/createdir.php",
  "DELETEDIR": "php/deletedir.php",
  "MOVEDIR": "php/movedir.php",
  "COPYDIR": "php/copydir.php",
  "RENAMEDIR": "php/renamedir.php",
  "FILESLIST": "php/fileslist.php",
  "UPLOAD": "php/upload.php",
  "DOWNLOAD": "php/download.php",
  "DOWNLOADDIR": "php/downloaddir.php",
  "DELETEFILE": "php/deletefile.php",
  "MOVEFILE": "php/movefile.php",
  "COPYFILE": "php/copyfile.php",
  "RENAMEFILE": "php/renamefile.php",
  "GENERATETHUMB": "php/thumb.php",
  "DEFAULTVIEW": "list",
  "FORBIDDEN_UPLOADS": "zip js jsp jsb mhtml mht xhtml xht php phtml php3 php4 php5 phps shtml jhtml pl sh py cgi exe application gadget hta cpl msc jar vb jse ws wsf wsc wsh ps1 ps2 psc1 psc2 msh msh1 msh2 inf reg scf msp scr dll msi vbs bat com pif cmd vxd cpl htpasswd htaccess",
  "ALLOWED_UPLOADS": "",
  "FILEPERMISSIONS": "0644",
  "DIRPERMISSIONS": "0755",
  "LANG": "auto",
  "DATEFORMAT": "dd/MM/yyyy HH:mm",
  "OPEN_LAST_DIR": "yes"
}',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '{
  "MODX": "initialize",
  "FILES_ROOT": "",
  "RETURN_URL_PREFIX": "",
  "SESSION_PATH_KEY": "",
  "THUMBS_VIEW_WIDTH": "140",
  "THUMBS_VIEW_HEIGHT": "120",
  "PREVIEW_THUMB_WIDTH": "100",
  "PREVIEW_THUMB_HEIGHT": "100",
  "MAX_IMAGE_WIDTH": "1000",
  "MAX_IMAGE_HEIGHT": "1000",
  "INTEGRATION": "tinymce4",
  "DIRLIST": "php/dirtree.php",
  "CREATEDIR": "php/createdir.php",
  "DELETEDIR": "php/deletedir.php",
  "MOVEDIR": "php/movedir.php",
  "COPYDIR": "php/copydir.php",
  "RENAMEDIR": "php/renamedir.php",
  "FILESLIST": "php/fileslist.php",
  "UPLOAD": "php/upload.php",
  "DOWNLOAD": "php/download.php",
  "DOWNLOADDIR": "php/downloaddir.php",
  "DELETEFILE": "php/deletefile.php",
  "MOVEFILE": "php/movefile.php",
  "COPYFILE": "php/copyfile.php",
  "RENAMEFILE": "php/renamefile.php",
  "GENERATETHUMB": "php/thumb.php",
  "DEFAULTVIEW": "list",
  "FORBIDDEN_UPLOADS": "zip js jsp jsb mhtml mht xhtml xht php phtml php3 php4 php5 phps shtml jhtml pl sh py cgi exe application gadget hta cpl msc jar vb jse ws wsf wsc wsh ps1 ps2 psc1 psc2 msh msh1 msh2 inf reg scf msp scr dll msi vbs bat com pif cmd vxd cpl htpasswd htaccess",
  "ALLOWED_UPLOADS": "",
  "FILEPERMISSIONS": "0644",
  "DIRPERMISSIONS": "0755",
  "LANG": "auto",
  "DATEFORMAT": "dd/MM/yyyy HH:mm",
  "OPEN_LAST_DIR": "yes"
}',
    ),
  ),
  'b4fa1ac8614d1da2ee9aaa8a54c10767' => 
  array (
    'criteria' => 
    array (
      'category' => 'TinyJSONGallery',
    ),
    'object' => 
    array (
      'id' => 6,
      'parent' => 1,
      'category' => 'TinyJSONGallery',
      'rank' => 0,
    ),
  ),
  '6e14e79f910a227e1c7aa867d9924975' => 
  array (
    'criteria' => 
    array (
      'name' => 'NatureAlbum_myGallery',
    ),
    'object' => 
    array (
      'id' => 9,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'NatureAlbum_myGallery',
      'description' => 'This is a sample album of the finest natural scenes',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '[{"Location": "assets/components/tinymcewrapper/gallery/stockImages/nature/&autoCreateThumb=1&justJSON=0&options=w=178,h=117,zc=t"},{"name":"SIOCMQf - Imgur.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/SIOCMQf - Imgur.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/SIOCMQf - Imgur.gif","hidden":"0","desc":"SIOCMQf - Imgur","index":"1","time":"1447783185","tag":"","lerror":"0"},{"name":"Seaofclouds - 2880x1800.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/Seaofclouds - 2880x1800.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/Seaofclouds - 2880x1800.gif","hidden":"0","desc":"Seaofclouds - 2880x1800","index":"2","time":"1447783184","tag":"","lerror":"0"},{"name":"Somniatis.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/Somniatis.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/Somniatis.gif","hidden":"0","desc":"Somniatis","index":"3","time":"1447783187","tag":"","lerror":"0"},{"name":"SpringWP1920.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/SpringWP1920.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/SpringWP1920.gif","hidden":"0","desc":"SpringWP1920","index":"4","time":"1447783189","tag":"","lerror":"0"},{"name":"Watercolor Sunset HD.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/Watercolor Sunset HD.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/Watercolor Sunset HD.gif","hidden":"0","desc":"Watercolor Sunset HD","index":"5","time":"1447783190","tag":"","lerror":"0"},{"name":"White Front HD.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/White Front HD.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/White Front HD.gif","hidden":"0","desc":"White Front HD","index":"6","time":"1447783190","tag":"","lerror":"0"},{"name":"sea nnfoam fhd 1920x1080.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/sea nnfoam fhd 1920x1080.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/sea nnfoam fhd 1920x1080.gif","hidden":"0","desc":"sea nnfoam fhd 1920x1080","index":"7","time":"1447783183","tag":"","lerror":"0"},{"name":"spring-1920x1080.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/spring-1920x1080.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/spring-1920x1080.gif","hidden":"0","desc":"spring-1920x1080","index":"8","time":"1447783188","tag":"","lerror":"0"}]',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '[{"Location": "assets/components/tinymcewrapper/gallery/stockImages/nature/&autoCreateThumb=1&justJSON=0&options=w=178,h=117,zc=t"},{"name":"SIOCMQf - Imgur.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/SIOCMQf - Imgur.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/SIOCMQf - Imgur.gif","hidden":"0","desc":"SIOCMQf - Imgur","index":"1","time":"1447783185","tag":"","lerror":"0"},{"name":"Seaofclouds - 2880x1800.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/Seaofclouds - 2880x1800.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/Seaofclouds - 2880x1800.gif","hidden":"0","desc":"Seaofclouds - 2880x1800","index":"2","time":"1447783184","tag":"","lerror":"0"},{"name":"Somniatis.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/Somniatis.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/Somniatis.gif","hidden":"0","desc":"Somniatis","index":"3","time":"1447783187","tag":"","lerror":"0"},{"name":"SpringWP1920.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/SpringWP1920.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/SpringWP1920.gif","hidden":"0","desc":"SpringWP1920","index":"4","time":"1447783189","tag":"","lerror":"0"},{"name":"Watercolor Sunset HD.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/Watercolor Sunset HD.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/Watercolor Sunset HD.gif","hidden":"0","desc":"Watercolor Sunset HD","index":"5","time":"1447783190","tag":"","lerror":"0"},{"name":"White Front HD.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/White Front HD.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/White Front HD.gif","hidden":"0","desc":"White Front HD","index":"6","time":"1447783190","tag":"","lerror":"0"},{"name":"sea nnfoam fhd 1920x1080.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/sea nnfoam fhd 1920x1080.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/sea nnfoam fhd 1920x1080.gif","hidden":"0","desc":"sea nnfoam fhd 1920x1080","index":"7","time":"1447783183","tag":"","lerror":"0"},{"name":"spring-1920x1080.gif","src":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/thumb/spring-1920x1080.gif","url":"/~blackhawkbowl/cms/assets/components/tinymcewrapper/gallery/stockImages/nature/spring-1920x1080.gif","hidden":"0","desc":"spring-1920x1080","index":"8","time":"1447783188","tag":"","lerror":"0"}]',
    ),
  ),
  'f9a9cd98f05756cab2c45dce59f7427c' => 
  array (
    'criteria' => 
    array (
      'name' => 'NatureAlbum_rowTpl',
    ),
    'object' => 
    array (
      'id' => 10,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'NatureAlbum_rowTpl',
      'description' => 'Sample tpl for frontend output of gallery items. Use MIGX\' getImageList or anything you like',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '<li data-index="[[+index]]" id=\'[[+rowCls]][[+idx]]\' class=\'[[+rowCls]]\' data-tag="[[+tag]]"><a class=\'[[+linkCls]]\' title="[[+name:stripString=`.png`]]" href=\'[[+url]]\'><img src=\'[[+src]]\' alt=\'[[+name:stripString=`.png`]]\' data-desc=\'[[+desc]]\' class=\'[[+imgCls]]\' data-name=\'[[+name]]\' data-tag="[[+tag]]">[[+name:stripString=`.png`]]</a></li>',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<li data-index="[[+index]]" id=\'[[+rowCls]][[+idx]]\' class=\'[[+rowCls]]\' data-tag="[[+tag]]"><a class=\'[[+linkCls]]\' title="[[+name:stripString=`.png`]]" href=\'[[+url]]\'><img src=\'[[+src]]\' alt=\'[[+name:stripString=`.png`]]\' data-desc=\'[[+desc]]\' class=\'[[+imgCls]]\' data-name=\'[[+name]]\' data-tag="[[+tag]]">[[+name:stripString=`.png`]]</a></li>',
    ),
  ),
  '0efdae6232483cc4c38b43b5637b5820' => 
  array (
    'criteria' => 
    array (
      'name' => 'NatureAlbum_rowTpl_bxslider',
    ),
    'object' => 
    array (
      'id' => 11,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'NatureAlbum_rowTpl_bxslider',
      'description' => 'Sample bxslider tpl for frontend output of gallery items. Use MIGX\' getImageList or anything you like',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '<li data-index="[[+index]]" id=\'[[+rowCls]][[+idx]]\' class=\'[[+rowCls]]\' data-tag="[[+tag]]">
  <img src="[[+url]]" title="[[+desc]] [[+tag:isnotempty=`([[+tag]])`]]" />
</li>',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<li data-index="[[+index]]" id=\'[[+rowCls]][[+idx]]\' class=\'[[+rowCls]]\' data-tag="[[+tag]]">
  <img src="[[+url]]" title="[[+desc]] [[+tag:isnotempty=`([[+tag]])`]]" />
</li>',
    ),
  ),
  'd5a9d7cccb87eecbd46f78c5d8f3414d' => 
  array (
    'criteria' => 
    array (
      'name' => 'sample_Gallery_BxSlider',
    ),
    'object' => 
    array (
      'id' => 12,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'sample_Gallery_BxSlider',
      'description' => 'This is a sample album of the finest natural scenes',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '<div class="twGalleryJson">
  <ul class="bxslider">
    [[!getImageList? 
    &o-ffset=`1`
    &sort=`[{"sortby":"index","sortdir":"DESC","sortmode":"numeric"},{"sortby":"name","sortdir":"ASC"}]`
    &tvname=`TinyJSONGalleryTV`
    &where =`{"hidden:=":"0"}`
    &tpl=`NatureAlbum_rowTpl_bxslider`
    &imgCls=`pic` &rowCls=`magic`
    &linkCls=`linked`]]
  </ul>
</div>',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<div class="twGalleryJson">
  <ul class="bxslider">
    [[!getImageList? 
    &o-ffset=`1`
    &sort=`[{"sortby":"index","sortdir":"DESC","sortmode":"numeric"},{"sortby":"name","sortdir":"ASC"}]`
    &tvname=`TinyJSONGalleryTV`
    &where =`{"hidden:=":"0"}`
    &tpl=`NatureAlbum_rowTpl_bxslider`
    &imgCls=`pic` &rowCls=`magic`
    &linkCls=`linked`]]
  </ul>
</div>',
    ),
  ),
  '634917681becebf2727c5930d68afcaf' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinyJSONGalleryOutput',
    ),
    'object' => 
    array (
      'id' => 5,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinyJSONGalleryOutput',
      'description' => 'Snippet to use for frontend presentation. Manipulate JSON code in all ways possible. Highly extensible. Better still use MIGX ultra powerful getImageList ... Enjoy! ',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '$galleryChunkOrJson = json_decode($galleryChunkOrJson, true);
$output = \'\';
$tpl = $modx->getOption(\'tpl\', $scriptProperties);
$i = 0;
$isFirst = true;
foreach ($galleryChunkOrJson as $gallery) {
  if ($isFirst) {
    $isFirst = false;
	continue;
  }
  if($gallery[\'hidden\'] == 1){
  continue;
  }
  $gallery[\'rowCls\'] = $rowCls;
  $gallery[\'linkCls\'] = $linkCls;
  $gallery[\'imgCls\'] = $imgCls;
  $gallery[\'idx\'] = $i++;
  $output.= $modx->getChunk($tpl, $gallery);
}
return $output;',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '$galleryChunkOrJson = json_decode($galleryChunkOrJson, true);
$output = \'\';
$tpl = $modx->getOption(\'tpl\', $scriptProperties);
$i = 0;
$isFirst = true;
foreach ($galleryChunkOrJson as $gallery) {
  if ($isFirst) {
    $isFirst = false;
	continue;
  }
  if($gallery[\'hidden\'] == 1){
  continue;
  }
  $gallery[\'rowCls\'] = $rowCls;
  $gallery[\'linkCls\'] = $linkCls;
  $gallery[\'imgCls\'] = $imgCls;
  $gallery[\'idx\'] = $i++;
  $output.= $modx->getChunk($tpl, $gallery);
}
return $output;',
    ),
  ),
  '0eb29865301f83298b19ab7ce5d02462' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinyJSONGalleryScanner',
    ),
    'object' => 
    array (
      'id' => 6,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinyJSONGalleryScanner',
      'description' => 'Auto-creation of thumb folder and thumbnails. Or use your own thumbs. Auto-creation of JSON code, or create your own. Enjoy!',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '/*
TinyJSON Gallery Scanner Snippet for TinymceWrapper
http://www.leofec.com/modx-revolution/
-donshakespeare in the MODx forum
Copyright 2015
*/
$autoCreateThumb = $modx->getOption("autoCreateThumb", $scriptProperties);
$justJSON = $modx->getOption("justJSON", $scriptProperties);
$options = $modx->getOption("options", $scriptProperties);
$partialOp = explode(\',\', $options);
$optionsArray = array();
array_walk($partialOp, function($val,$key) use(&$optionsArray){
  list($key, $value) = explode(\'=\', $val);
  $optionsArray[$key] = $value;
});
$options = $optionsArray;
//get inputedPath plus base, whether user has autoCreateThumb on or not
$inputedFullPath = MODX_BASE_PATH . $modx->getOption("inputedPath", $scriptProperties);
//full url to full image
$largeUrl = MODX_BASE_URL . $modx->getOption("inputedPath", $scriptProperties);
//full path to thumb image if autoCreateThumb is on
$thumbPath = MODX_BASE_PATH . $modx->getOption("inputedPath", $scriptProperties) . "thumb/";
//full url to full image if autoCreateThumb is off, and user has own thumbs(must be child of parent folder :) )
$thumbParentUrl = dirname($largeUrl) . "/";
$thumbUrl = $thumbParentUrl . "thumb/";
$thumbInfo = \'\';
echo \'<html><head><script>parent.loadingThrobber(0)</script></head><body style="word-break: break-all;font-size: 14px;">\';
if ($autoCreateThumb == 1 && $options !== 0) {
  $thumbParentUrl = $largeUrl;
  $thumbUrl = $largeUrl . "thumb/";
  if ($justJSON == 0) {
    $thumbInfo = \'<br><b>THUMBNAILS</b> <i>have been regenerated in thumb folder</i>\';
  }
}

if (file_exists($inputedFullPath)) {
  if ($autoCreateThumb && $justJSON == 0) {
    if (!file_exists($thumbPath)) {
      mkdir($thumbPath, 0777, true);
    }
    $checkResizer = MODX_CORE_PATH . \'components/resizer/model/\';
    if (file_exists($checkResizer)) {
      $modx->loadClass(\'Resizer\', MODX_CORE_PATH . \'components/resizer/model/\', true, true);
      $resizer = new Resizer($modx);
      $resizer->debug = true;
    }
    if(!$resizer){
      $thumbInfo = \'<br><b><a href="https://modx.com/extras/package/resizer" target="_blank">MODX Resizer</a></b> <i> is needed to create thumbnails<br>&justJSON was set to 0 - but no thumbs was created, only JSON</i>\';
    }
  }
  $images = glob($inputedFullPath . "*.{jpg,png,gif}", GLOB_BRACE);
  if ($images) {
    $comma = \'\';
    $output = "<b>SCANNING:  </b><i>" . $inputedFullPath . "</i><br><br><div spellcheck=\'false\' contenteditable=\'true\' style=\'border:2px dotted red; height: 200px !important;font-size: 12px; overflow: auto;overflow-x: hidden\'>[";
    $output.= \'<b>{"Location": "\' . $modx->getOption("inputedPath", $scriptProperties) . \'&autoCreateThumb=\'.$autoCreateThumb.\'&justJSON=\'.$justJSON.\'&options=\'.$modx->getOption("options", $scriptProperties).\'"},</b>\';
    $index = 0;
    foreach ($images as $image) {
      $index ++;
      $timeStamp = filemtime($image);
      $image = pathinfo($image);
      $file = $image["filename"];
      $ext = $image["extension"];
      $fileExt = $file . "." . $ext;
      $thumbSrc = $thumbUrl . $fileExt;
      $thumbParentUrlFname = $thumbParentUrl . $fileExt;
      if ($justJSON == 0 && $autoCreateThumb == 1 && $options !== 0) {
        if($resizer){
          $resizer->processImage($inputedFullPath . $fileExt, $thumbPath . $fileExt, $options);
        }
      }
      $cleanfile = preg_replace(\'/[^A-Za-z0-9\\-]/\', \' \', $file);
      $output.= $comma . \'{"name":"\' . $file.\'.\'.$ext . \'","src":"\' . $thumbSrc . \'","url":"\' . $thumbParentUrlFname . \'","hidden":"0","desc":"\' . $cleanfile . \'","index":"\' . $index . \'","time":"\' . $timeStamp . \'","tag":"","lerror":"0"}\';
      $comma = \',\';
    }
    $output.= "]</div><br><b>COPY CODE ABOVE</b> <i>and paste over your current JSON, and then press the option \'Build from Current JSON\'</i><br>".$thumbInfo."</body></html>";
    return $output;
  } 
  else {
    echo "<br><br><br>No valid files (jpg,png,gif) in <b>" . $modx->getOption("inputedPath", $scriptProperties) . "</b>";
  }
} 
else {
  echo "<br><br><br>The folder  <b>" . $modx->getOption("inputedPath", $scriptProperties) . "</b> does not exist";
}
echo \'</body></html>\';',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '/*
TinyJSON Gallery Scanner Snippet for TinymceWrapper
http://www.leofec.com/modx-revolution/
-donshakespeare in the MODx forum
Copyright 2015
*/
$autoCreateThumb = $modx->getOption("autoCreateThumb", $scriptProperties);
$justJSON = $modx->getOption("justJSON", $scriptProperties);
$options = $modx->getOption("options", $scriptProperties);
$partialOp = explode(\',\', $options);
$optionsArray = array();
array_walk($partialOp, function($val,$key) use(&$optionsArray){
  list($key, $value) = explode(\'=\', $val);
  $optionsArray[$key] = $value;
});
$options = $optionsArray;
//get inputedPath plus base, whether user has autoCreateThumb on or not
$inputedFullPath = MODX_BASE_PATH . $modx->getOption("inputedPath", $scriptProperties);
//full url to full image
$largeUrl = MODX_BASE_URL . $modx->getOption("inputedPath", $scriptProperties);
//full path to thumb image if autoCreateThumb is on
$thumbPath = MODX_BASE_PATH . $modx->getOption("inputedPath", $scriptProperties) . "thumb/";
//full url to full image if autoCreateThumb is off, and user has own thumbs(must be child of parent folder :) )
$thumbParentUrl = dirname($largeUrl) . "/";
$thumbUrl = $thumbParentUrl . "thumb/";
$thumbInfo = \'\';
echo \'<html><head><script>parent.loadingThrobber(0)</script></head><body style="word-break: break-all;font-size: 14px;">\';
if ($autoCreateThumb == 1 && $options !== 0) {
  $thumbParentUrl = $largeUrl;
  $thumbUrl = $largeUrl . "thumb/";
  if ($justJSON == 0) {
    $thumbInfo = \'<br><b>THUMBNAILS</b> <i>have been regenerated in thumb folder</i>\';
  }
}

if (file_exists($inputedFullPath)) {
  if ($autoCreateThumb && $justJSON == 0) {
    if (!file_exists($thumbPath)) {
      mkdir($thumbPath, 0777, true);
    }
    $checkResizer = MODX_CORE_PATH . \'components/resizer/model/\';
    if (file_exists($checkResizer)) {
      $modx->loadClass(\'Resizer\', MODX_CORE_PATH . \'components/resizer/model/\', true, true);
      $resizer = new Resizer($modx);
      $resizer->debug = true;
    }
    if(!$resizer){
      $thumbInfo = \'<br><b><a href="https://modx.com/extras/package/resizer" target="_blank">MODX Resizer</a></b> <i> is needed to create thumbnails<br>&justJSON was set to 0 - but no thumbs was created, only JSON</i>\';
    }
  }
  $images = glob($inputedFullPath . "*.{jpg,png,gif}", GLOB_BRACE);
  if ($images) {
    $comma = \'\';
    $output = "<b>SCANNING:  </b><i>" . $inputedFullPath . "</i><br><br><div spellcheck=\'false\' contenteditable=\'true\' style=\'border:2px dotted red; height: 200px !important;font-size: 12px; overflow: auto;overflow-x: hidden\'>[";
    $output.= \'<b>{"Location": "\' . $modx->getOption("inputedPath", $scriptProperties) . \'&autoCreateThumb=\'.$autoCreateThumb.\'&justJSON=\'.$justJSON.\'&options=\'.$modx->getOption("options", $scriptProperties).\'"},</b>\';
    $index = 0;
    foreach ($images as $image) {
      $index ++;
      $timeStamp = filemtime($image);
      $image = pathinfo($image);
      $file = $image["filename"];
      $ext = $image["extension"];
      $fileExt = $file . "." . $ext;
      $thumbSrc = $thumbUrl . $fileExt;
      $thumbParentUrlFname = $thumbParentUrl . $fileExt;
      if ($justJSON == 0 && $autoCreateThumb == 1 && $options !== 0) {
        if($resizer){
          $resizer->processImage($inputedFullPath . $fileExt, $thumbPath . $fileExt, $options);
        }
      }
      $cleanfile = preg_replace(\'/[^A-Za-z0-9\\-]/\', \' \', $file);
      $output.= $comma . \'{"name":"\' . $file.\'.\'.$ext . \'","src":"\' . $thumbSrc . \'","url":"\' . $thumbParentUrlFname . \'","hidden":"0","desc":"\' . $cleanfile . \'","index":"\' . $index . \'","time":"\' . $timeStamp . \'","tag":"","lerror":"0"}\';
      $comma = \',\';
    }
    $output.= "]</div><br><b>COPY CODE ABOVE</b> <i>and paste over your current JSON, and then press the option \'Build from Current JSON\'</i><br>".$thumbInfo."</body></html>";
    return $output;
  } 
  else {
    echo "<br><br><br>No valid files (jpg,png,gif) in <b>" . $modx->getOption("inputedPath", $scriptProperties) . "</b>";
  }
} 
else {
  echo "<br><br><br>The folder  <b>" . $modx->getOption("inputedPath", $scriptProperties) . "</b> does not exist";
}
echo \'</body></html>\';',
    ),
  ),
  '7a6cc3fecd3c37c95749239cee6e9249' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinyJSONGalleryTV',
    ),
    'object' => 
    array (
      'id' => 20,
      'source' => 0,
      'property_preprocess' => 0,
      'type' => 'textarea',
      'name' => 'TinyJSONGalleryTV',
      'caption' => 'TinyJSONGalleryTV',
      'description' => 'Transform TV to dazzling gallery album. This TV must be plain textarea (input type). Go to TinymceWrapper Plugin to customize this Gallery addon.',
      'editor_type' => 0,
      'category' => 6,
      'locked' => 0,
      'elements' => '',
      'rank' => 5,
      'display' => 'default',
      'default_text' => '[{"Location": "assets/components/tinymcewrapper/gallery/stockImages/nature/&autoCreateThumb=1&justJSON=0&options=w=178,h=117,zc=t"}]',
      'properties' => 'a:0:{}',
      'input_properties' => 'a:3:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";}',
      'output_properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '[{"Location": "assets/components/tinymcewrapper/gallery/stockImages/nature/&autoCreateThumb=1&justJSON=0&options=w=178,h=117,zc=t"}]',
    ),
  ),
  'a0378e58d5a610079e2362115a6ce498' => 
  array (
    'criteria' => 
    array (
      'category' => 'TinyMagicPublisher',
    ),
    'object' => 
    array (
      'id' => 7,
      'parent' => 1,
      'category' => 'TinyMagicPublisher',
      'rank' => 0,
    ),
  ),
  'bd96dc7d25580637bca5454a7cfb4df0' => 
  array (
    'criteria' => 
    array (
      'name' => 'twEDITnpOuterTpl',
    ),
    'object' => 
    array (
      'id' => 13,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'twEDITnpOuterTpl',
      'description' => 'Custom Outer Tpl chunk for entire NewsPublisher Edit Form',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => '<div class="newspublisher">
        <!--<h2 id="newspublisherHeader">[[%np_main_header]]</h2>-->
		<h2 id="newspublisherHeader">Editing Resource</h2>
        [[!+np.error_header:ifnotempty=`<h3>[[!+np.error_header]]</h3>`]]
        [[!+np.errors_presubmit:ifnotempty=`[[!+np.errors_presubmit]]`]]
        [[!+np.errors_submit:ifnotempty=`[[!+np.errors_submit]]`]]
        [[!+np.errors:ifnotempty=`[[!+np.errors]]`]]
  <form id="newspublisherForm" action="[[~[[*id]]? &scheme=`full`  &edit=`true`]]" method="post">
            <input name="hidSubmit" type="hidden" id="hidSubmit" value="true" />
        [[+npx.insert]]
         <span class = "buttons">
             <input class="submit" id="np_submit_button" type="submit" name="SubmitTW" value="Submit" />
             [[+np_duplicate_button]]
             [[+np_delete_button]]
             <input type="button" id="np_cancel_button" class="cancel" name="Cancel" value="Cancel" onclick="window.location = \'[[+np.cancel_url]]\' " />
         </span>
        [[+np.post_stuff]]
  </form>
</div>',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<div class="newspublisher">
        <!--<h2 id="newspublisherHeader">[[%np_main_header]]</h2>-->
		<h2 id="newspublisherHeader">Editing Resource</h2>
        [[!+np.error_header:ifnotempty=`<h3>[[!+np.error_header]]</h3>`]]
        [[!+np.errors_presubmit:ifnotempty=`[[!+np.errors_presubmit]]`]]
        [[!+np.errors_submit:ifnotempty=`[[!+np.errors_submit]]`]]
        [[!+np.errors:ifnotempty=`[[!+np.errors]]`]]
  <form id="newspublisherForm" action="[[~[[*id]]? &scheme=`full`  &edit=`true`]]" method="post">
            <input name="hidSubmit" type="hidden" id="hidSubmit" value="true" />
        [[+npx.insert]]
         <span class = "buttons">
             <input class="submit" id="np_submit_button" type="submit" name="SubmitTW" value="Submit" />
             [[+np_duplicate_button]]
             [[+np_delete_button]]
             <input type="button" id="np_cancel_button" class="cancel" name="Cancel" value="Cancel" onclick="window.location = \'[[+np.cancel_url]]\' " />
         </span>
        [[+np.post_stuff]]
  </form>
</div>',
    ),
  ),
  '33686eddb0da2bb95e2f780544131d30' => 
  array (
    'criteria' => 
    array (
      'name' => 'twBROWSERnpImageTpl',
    ),
    'object' => 
    array (
      'id' => 14,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'twBROWSERnpImageTpl',
      'description' => 'Custom Tpl chunk for NewsPublisher image TVs',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => '    <div id="np-[[+npx.fieldName]]-container" class="np-image">
        [[+np.error_[[+npx.fieldName]]]]
        <label class="fieldlabel" for="np-[[+npx.fieldName]]" title="[[+npx.help]]">[[+npx.caption]]: </label>
        <input name="[[+npx.fieldName]]" class="image" id="np-[[+npx.fieldName]]" type="text"  value="[[+np.[[+npx.fieldName]]]]" />
        <button type="button" onclick="autoFileBrowser(\'np-[[+npx.fieldName]]\')">[[%np_launch_image_browser]]</button>
    </div>',
      'locked' => 0,
      'properties' => NULL,
      'static' => 0,
      'static_file' => '',
      'content' => '    <div id="np-[[+npx.fieldName]]-container" class="np-image">
        [[+np.error_[[+npx.fieldName]]]]
        <label class="fieldlabel" for="np-[[+npx.fieldName]]" title="[[+npx.help]]">[[+npx.caption]]: </label>
        <input name="[[+npx.fieldName]]" class="image" id="np-[[+npx.fieldName]]" type="text"  value="[[+np.[[+npx.fieldName]]]]" />
        <button type="button" onclick="autoFileBrowser(\'np-[[+npx.fieldName]]\')">[[%np_launch_image_browser]]</button>
    </div>',
    ),
  ),
  '7ddf5f40a52537913b9a278ca0a4441d' => 
  array (
    'criteria' => 
    array (
      'name' => 'twCREATEnpOuterTpl',
    ),
    'object' => 
    array (
      'id' => 15,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'twCREATEnpOuterTpl',
      'description' => 'Custom Outer Tpl chunk for entire NewsPublisher Create Form',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => '<div class="newspublisher">
       <!-- <h2 id="newspublisherHeader">[[%np_main_header]]</h2>-->
	   <h2 id="newspublisherHeader">Creating Resource</h2>
        [[!+np.error_header:ifnotempty=`<h3>[[!+np.error_header]]</h3>`]]
        [[!+np.errors_presubmit:ifnotempty=`[[!+np.errors_presubmit]]`]]
        [[!+np.errors_submit:ifnotempty=`[[!+np.errors_submit]]`]]
        [[!+np.errors:ifnotempty=`[[!+np.errors]]`]]
  <form id="newspublisherForm" action="[[~[[*id]]? &scheme=`full`  &create=`true`]]" method="post">
            <input name="hidSubmit" type="hidden" id="hidSubmit" value="true" />
        [[+npx.insert]]
         <span class = "buttons">
             <input class="submit" id="np_submit_button" type="submit" name="SubmitTW" value="Submit" />
             [[+np_duplicate_button]]
             [[+np_delete_button]]
             <input type="button" id="np_cancel_button" class="cancel" name="Cancel" value="Cancel" onclick="window.location = \'[[+np.cancel_url]]\' " />
         </span>
        [[+np.post_stuff]]
  </form>
</div>',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '<div class="newspublisher">
       <!-- <h2 id="newspublisherHeader">[[%np_main_header]]</h2>-->
	   <h2 id="newspublisherHeader">Creating Resource</h2>
        [[!+np.error_header:ifnotempty=`<h3>[[!+np.error_header]]</h3>`]]
        [[!+np.errors_presubmit:ifnotempty=`[[!+np.errors_presubmit]]`]]
        [[!+np.errors_submit:ifnotempty=`[[!+np.errors_submit]]`]]
        [[!+np.errors:ifnotempty=`[[!+np.errors]]`]]
  <form id="newspublisherForm" action="[[~[[*id]]? &scheme=`full`  &create=`true`]]" method="post">
            <input name="hidSubmit" type="hidden" id="hidSubmit" value="true" />
        [[+npx.insert]]
         <span class = "buttons">
             <input class="submit" id="np_submit_button" type="submit" name="SubmitTW" value="Submit" />
             [[+np_duplicate_button]]
             [[+np_delete_button]]
             <input type="button" id="np_cancel_button" class="cancel" name="Cancel" value="Cancel" onclick="window.location = \'[[+np.cancel_url]]\' " />
         </span>
        [[+np.post_stuff]]
  </form>
</div>',
    ),
  ),
  'bf814007205484bae153c0b775298ef4' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPpublishButtonsTpl',
    ),
    'object' => 
    array (
      'id' => 16,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPpublishButtonsTpl',
      'description' => 'That fixed Publish Button - with resource Options, save buttons etc - all TinyMCE functions - lexicons',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => 'function popNPfields(target, title, width, height, okay) {
  target = $(target);
  var $inline = $(\'<div>\').hide().insertBefore(target);
  tinymce.get("tmpTempEditor").windowManager.open({
    title: title ? title : "Modal Window",
    width: 550,
    height: height ? height : $(target).height(),
    classes: "popNPfields",
    style: "padding:10px;",
    autoScroll: false,
    items: [{
      classes: "popNPfields",
      type: \'container\',
      onPostRender: function() {
        $(target).appendTo(".mce-popNPfields > .mce-container-body");
        $(".mce-popNPfields .mce-close").on("click", function(e) {
          $inline.replaceWith(target);
          tinymce.get("tmpTempEditor").windowManager.close();
          e.preventDefault;
        });
      }
    }],
    buttons: [{
      text: okay ? okay : "Okay",
      onclick: function() {
        $inline.replaceWith(target);
        tinymce.get("tmpTempEditor").windowManager.close();
      }
    }]
  });
}

function stateToggle() {
  var self = this;
  tinymce.each(tinymce.editors, function(editor) {
    var elm = $(editor.targetElm).data("tiny");
    if(elm) {
      editor.on(\'nodeChange\', function() {
        self.disabled(!editor.isDirty())
      });
    }
  });
}
function saveDetails(){
  //add as many fields as you like
  twNPhideFromMenu = twNPpublished = twNPpublishedby = twNPpublishedon = twNPpubDate = twNPunpubDate = twNPmenuTitle = twNPmenuIndex = twNPalias = twNPtags =  twNPrichtext = twNPlongTitle = twNPparentList = twNPtemplateList = twNPdeleted = twNPtemplateListSelVal = twNPparentListSelVal = \'\';
  if ($("#np-published").length) {
    twNPpublished = {
      label: \'Published\',
      type: \'checkbox\',
      autofocus: false,
      onchange: function(){
        var checkbox = document.getElementById("np-published");
        if(this.value()){
          checkbox.checked = true;
          checkbox.value = 1;
          $("input[name=published]").val(1);
        }
        else{
          checkbox.checked = false;
          checkbox.value = \'\';
          $("input[name=published]").val(\'\');
        }
      },
      onPostRender: function(){
        if(document.getElementById(\'np-published\').checked) {
          this.value(1);
        }
      }
    }
  }
  if ($("#np-richtext").length) {
    twNPrichtext = {
      label: \'Richtext\',
      type: \'checkbox\',
      autofocus: false,
      onchange: function(){
        var checkbox = document.getElementById("np-richtext");
        if(this.value()){
          checkbox.checked = true;
          checkbox.value = 1;
        }
        else{
          checkbox.checked = false;
          checkbox.value = \'\';
        }
      },
      onPostRender: function(){
        if(document.getElementById(\'np-richtext\').checked) {
          this.value(1);
        }
      }
    }
  }
  if($("#np-hidemenu").length){
    twNPhideFromMenu = {
      label: \'Hide from Menu\',
      type: \'checkbox\',
      onchange: function(){
        var checkbox = document.getElementById("np-hidemenu");
        if(this.value()){
          checkbox.checked = true;
          checkbox.value = 1;
        }
        else{
          checkbox.checked = false;
          checkbox.value = \'\';
        }
      },
      onPostRender: function(){
        if(document.getElementById(\'np-hidemenu\').checked) {
          this.value(1);
        }
      }
    }
  }
  if ($("#pub_date").length) {
    twNPpubDate = {
      type: \'textbox\',
      classes: \'pubDate\',
      tooltip: \'Publish Future Date: Y-M-D format\',
      label: \'Publish Date\',
      maxWidth: 100,
      onchange: function(){
        $("#pub_date").val(this.value())
      },
      onPostRender:function(){
        this.value($("#pub_date").val());
      }
    }
  }
  if ($("#publishedon").length) {
    twNPpublishedon = {
      type: \'textbox\',
      classes: \'publishedon\',
      tooltip: \'Published Date: Y-M-D format\',
      label: \'Published On\',
      maxWidth: 100,
      onchange: function(){
        $("#publishedon").val(this.value())
      },
      onPostRender:function(){
        this.value($("#publishedon").val());
      }
    }
  }
  if ($("#np-publishedby").length) {
    twNPpublishedby = {
      type: \'textbox\',
      classes: \'publishedby\',
      tooltip: \'Published By\',
      label: \'Published By\',
      maxWidth: 35,
      onchange: function(){
        $("#np-publishedby").val(this.value())
      },
      onPostRender:function(){
        this.value($("#np-publishedby").val());
      }
    }
  }
  if ($("#unpub_date").length) {
    twNPunpubDate = {
      type: \'textbox\',
      classes: \'unpubDate\',
      tooltip: \'Unpublish Date: Y-M-D format\',
      label: \'Unpublish Date\',
      maxWidth: 100,
      onchange: function(){
        $("#unpub_date").val(this.value())
      },
      onPostRender:function(){
        this.value($("#unpub_date").val());
      }
    }
  }
  if ($("#np-menutitle").length) {
    twNPmenuTitle = {
      type: \'textbox\',
      classes: \'menuTitle\',
      tooltip: \'Menu Title\',
      label: \'Menu Title\',
      onchange: function(){
        $("#np-menutitle").val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-menutitle").val());
      }
    }
  }
  if($(\'#np-existingTags option\').length){
    var maxWidthTags = 191;
    var tooltipsTags = \'All Tags are sanitized: no spaces, special xters etc. Use Drop Down to access Existing Tags from your site - duplicates will be removed upon selection\';
    var usedNames = {};
    $("#np-existingTags option").each(function () {
      if(usedNames[this.text]) {
          $(this).attr({\'title\':\'Duplicates are disabled\',\'disabled\':\'disabled\'});
      } else {
          usedNames[this.text] = this.value;
      }
    });
    $("#np-existingTags optgroup").each(function () {
      if(!$(this).children(\'option\').length){
        $(this).remove()
      }
    })
  }
  else{
    var maxWidthTags = \'\';
    var tooltipsTags = \'Resource Tags\';
  }
  if ($("#np-"+twTAGtvID).length) {
    twNPtags = {
      type: \'textbox\',
      classes: \'articlestags\',
      tooltip: tooltipsTags,
      label: \'Resource Tags\',
      maxWidth: maxWidthTags,
      onchange: function(){
        $("#np-"+twTAGtvID).val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-"+twTAGtvID).val());
        if($(\'#np-existingTags option\').length){
          this.value($("#np-"+twTAGtvID).val()
          // .replace(/[^-,_A-Za-z0-9]+/g, \'\')     // .replace(/ /g,\'\')
          .replace(/[^,A-Za-z0-9]+/g, \'\')
          .replace(/^[,\\s]+|[,\\s]+$/g, \'\')
          .replace(/,[,\\s]*,/g, \',\'));
          $(\'.mce-articlestags\').attr("placeholder", "Please enter valid Tags here...").css({\'padding-right\':\'25px\'}).parent().append( $(\'#np-existingTags\').clone().addClass(\'mce-np-existingTags\').css({\'position\': \'absolute\',\'top\':\'0\',\'bottom\':\'0\',\'margin\':\'auto\', \'right\':\'0\',\'width\':\'18px\'}) )

          $(\'.mce-np-existingTags option\')
          .each(function(){
              var optText = $(this).text()
              .replace(/[^,A-Za-z0-9]+/g, \'\');
              $(this).html(optText);
              $(this).val(optText);
            })
          $(\'.mce-np-existingTags\')
          .prop(\'selectedIndex\',-1)
          .on(\'change\',function(){
              var thisVal = $( ".mce-np-existingTags .twTagOptions:selected" ).val();
              var val = $(".mce-articlestags").val()
              .replace(/[^,A-Za-z0-9]+/g, \'\')
              .replace(/^[,\\s]+|[,\\s]+$/g, \'\')
              .replace(/,[,\\s]*,/g, \',\');
              var tags = val ? val.split(\',\') : [];
              var index = tags.indexOf(thisVal);
              if (index > -1) {
                tags.splice(index, 1);
              } else {
                tags.push(thisVal);
              }
              $("#np-"+twTAGtvID+", .mce-articlestags").val(tags.join(\',\'));
              // $(\'.mce-np-existingTags\').prop(\'selectedIndex\',-1);
              });
        }
      }
    }
  }
  if ($("#np-menuindex").length) {
    twNPmenuIndex = {
      type: \'textbox\',
      classes: \'menuindex\',
      tooltip: \'Menu Index\',
      label: \'Menu Index\',
      maxWidth: 35,
      onchange: function(){
        $("#np-menuindex").val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-menuindex").val());
      }
    }
  }
  if ($("#np-alias").length) {
    twNPalias = {
      type: \'textbox\',
      classes: \'alias\',
      tooltip: \'Resource Alias\',
      label: \'Resource Alias\',
      onchange: function(){
        $("#np-alias").val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-alias").val());
      }
    }
  }
  if ($("#np-longtitle").length) {
    twNPlongTitle = {
      type: \'textbox\',
      classes: \'longTitle\',
      tooltip: \'Long Title\',
      label: \'Long Title\',
      onchange: function(){
        $("#np-longtitle").val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-longtitle").val());
      }
    }
  }
  if ($("#np-parent-container select option").length) {
    twNPparentListValues = [];
    $("#np-parent-container option").each(function(){
      var text = $(this).text();
      var val = $(this).val();
      if($(this).is(\':selected\')){
        twNPparentListSelVal = val;
      }
      twNPparentListValues.push(
        {
          text: text,
          value:val,
          onselect: function() {
            $("#np-parent-container select").val(this.value());
          }
        }
      )
    });
    if(twNPparentListSelVal){
      twNPparentList = {
        type: \'listbox\',
        classes: \'parentList\',
        values: twNPparentListValues,
        tooltip: \'Parent Resources\',
        label: \'Parent\',
        onPostRender:function(){
          this.value(twNPparentListSelVal);
        }
      }
    }
  }
  if ($("#np-template-container select option").length) {
    twNPtemplateListValues = [];
    $("#np-template-container option").each(function(){
      var text = $(this).text();
      var val = $(this).val();
      if($(this).is(\':selected\')){
        twNPtemplateListSelVal = val;
      }
      twNPtemplateListValues.push(
        {
          text: text,
          value:val,
          onselect: function() {
            $("#np-template-container select").val(this.value());
          }
        }
      )
    });
    if(twNPtemplateListSelVal){
      twNPtemplateList = {
        type: \'listbox\',
        classes: \'templateList\',
        values: twNPtemplateListValues,
        tooltip: \'Templates\',
        label: \'Template\',
        onPostRender:function(){
          this.value(twNPtemplateListSelVal);
        }
      }
    }
  }
  if ($("#np-deleted").length) {
    twNPdeleted = {
      type: \'checkbox\',
      classes: \'npDeleted\',
      // tooltip: \'Marks the resource for deletion\',
      label: \'Mark Resource For Deletion\',
      onchange: function(){
        var checkbox = document.getElementById("np-deleted");
        if(this.value()){
          checkbox.checked = true;
          checkbox.value = 1;
          var tthis = this;
          if(npCreate == 1){
            tinymce.get("tmpTempEditor").windowManager.confirm("Delete this resource immediately?", function(s) {
              if (s) {
                $("#newspublisherForm").submit();
              } else {
                checkbox.checked = false;
                checkbox.value = \'\';
                tthis.value(0);
              }
            });
          } else{
              tinymce.get("tmpTempEditor").windowManager.alert("Not applicable here", function(){tthis.value(0)});
          }
        }
        else{
          checkbox.checked = false;
          checkbox.value = \'\';
        }
      },
      onPostRender:function(){
        if(document.getElementById(\'np-deleted\').checked) {
          this.value(1);
        }
      }
    }
  }

  tinymce.get("tmpTempEditor").windowManager.open({
    title: \'MODX Resource Details\',
    classes: \'resourceDetails\',
    bodyType: \'tabpanel\',
    activateTab: 2,
    // width: 300,
    // minHeight: 255,
    autoScroll:true,
    onPostRender: function() {
      // $(".mce-resourceDetails .mce-close").remove()
      // $(".mce-resourceDetails .mce-foot .mce-widget *:not(.mce-saveMODX)").remove()
      // var thisAppendTo = $(".mce-foot > .mce-container-body.mce-abs-layout").attr(\'id\')
      // tinymce.ui.Factory.create({
      //   type: \'button\',
      // }).renderTo(document.getElementById(thisAppendTo));
    },
    body: [
    {
      title: "Published (by)",
      type: "form",
      items: [
        twNPpublished,
        twNPpublishedon,
        twNPpubDate,
        twNPunpubDate,
        twNPpublishedby
      ]
    },
    {
      title: "Resource Details",
      type: "form",
      items: [
        twNPtags,
        twNPalias,
        twNPlongTitle,
        twNPparentList,
        twNPtemplateList,
        twNPrichtext
      ]
    },
    {
      title: "Navigation",
      type: "form",
      items: [
        twNPhideFromMenu,
        twNPmenuTitle,
        twNPmenuIndex
      ]
    },
    {
      title: "Delete",
      style: \'color:red!important;\',
      type: "form",
      items: [
        twNPdeleted
      ]
    }
    ],
    buttons:[
    {
      text: \'Save Changes to MODX\',
      tooltip: \'This will Save to MODX all your changes\',
      classes: \'saveMODX\',
      active: true,
      autofocus: true,
      style: \'position:absolute;top:10px;left:8px;\',
      onclick:saveArticle
    }

    ]
  })
}

function saveArticle() {
  dontDoThat = 0;
  tinymce.each(tinymce.editors, function(editor) {
    var elm = $(editor.targetElm).data("tiny");
    if(elm) {
      var tinyName = editor.getParam("name");
      var more = "";
      if($(".mce-resourceDetails").length){
        var more = "Close the Resource Details modal window to see the field\\n\\r";
      }
      if ($(editor.getBody()).text().trim() == tinyName) {
        dontDoThat++;
        if(dontDoThat < 2){
          editor.windowManager.alert(tinyName + " (the field in focus) is not a valid input, please change\\n\\r"+more);
          }
      }
      else if (dontDoThat < 1 && $(editor.getBody()).text().trim() !== tinyName && !$(editor.getBody()).text().trim().length) {
        dontDoThat++;
        if(dontDoThat < 2){
          editor.windowManager.alert(tinyName + " (the field in focus) is empty, please fill\\n\\r"+more);
        }
      }
    }
  })
  if (dontDoThat < 1) {
    tinymce.get("tmpTempEditor").windowManager.confirm("Save resource?", function(s) {
      if (s) {
        tinymce.triggerSave();
        $("#newspublisherForm").submit();
      } else {}
    });
  }
}

function saveButton(editor) {
  if (!editor.isDirty()) {
    $(".mce-npPublishButton").removeClass("mce-active");
    $(\'.mce-npPublishButton i.mce-ico\').addClass(\'mce-i-fullpage\').removeClass(\'mce-i-save\')
    // $(".mce-npPublishButton, .mce-discardMODX").addClass("mce-disabled").attr("aria-disabled", true).children("button").attr("disabled", "disabled");
  } else {
    $(".mce-npPublishButton").addClass("mce-active");
    $(\'.mce-npPublishButton i.mce-ico\').addClass(\'mce-i-save\').removeClass(\'mce-i-fullpage\')
    // $(".mce-npPublishButton, .mce-discardMODX").fadeIn("slow").removeClass("mce-disabled").removeAttr("aria-disabled").children().removeAttr("disabled");
  }
  var pID = $("#" + editor.id).attr("data-tiny");
  if(editor.getParam("external_plugins",1).twExoticMarkdownEditor){
    $("#" + pID).val(saveMyFootMarks(editor.getContent({source:"true"})));
  }
  else{
    $("#" + pID).val(tinymce.activeEditor.getContent());
  }
}

function createButtons() {
  //common buttons + functions for each TinyMCE init, remove to override, 
  // var count =0; //debug stuff
  if(!$("#tmpTempEditor").length){
    $("body").append("<input id=tmpTempEditor type=hidden value=\'\' />");
    tinymce.init({
      selector: "#tmpTempEditor",
      skin_url: tinymce.activeEditor.getParam("skin_url", "[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles"),
      inline:true,
      forced_root_block : "",
      force_br_newlines : false,
      force_p_newlines : false
    })
  }
  tinymce.each(tinymce.editors, function(editor) {
    var elm = $(editor.targetElm).data("tiny");
    if(elm) {
      // console.log("number of legal Editor instances  .."+count++); //debug stuff
      // editor.on("keyup focus paste mouseup SetContent", function() {
      editor.on("change", function() {
        saveButton(editor);
      });
      editor.addMenuItem("reset", {
        title: "Cancel all changes!",
        text: "Discard changes",
        onPostRender: stateToggle,
        classes: \'discardMODX\',
        icon: "undo",
        onclick: function() {
          editor.windowManager.confirm("Really discard all changes?", function(s) {
            if (s) {
              $(editor.getBody()).html(editor.startContent)
            } else {}
          })
        }
      });
    }
  });
  //end common buttons
  tinymce.ui.Factory.create({
    type: \'menubutton\',
    tooltip: "Resource details ... other tasks",
    icon: "fullpage",
    classes: "npPublishButton",
    text: "PUBLISH ",
    onclick: function(){
      if(!$("form#newspublisherForm").length || !$(".fielderrormessage").length){
        npErrors();
        return false;
      }
    },
    onPostRender: function(){
      if (!$("#twHelp").length) {
        $("<div id=\'twHelp\' style=\'height:300px; white-space: normal !important;\'><p>Hi there.<br>I know you are enjoying this stuff, well, so am I.<br>You will see that it is easy to extend this frontend magic idea to suit your case.<br>I will offer premium support for anything surpassing 5 minutes of my precious time.<br><br>This project was inspired by my zeal to surpass <strong>medium.com</strong> and <strong>createPHP.</strong> Be thou the judge!<br>I know the next thing you want is AJAX, well, ask Bob Ray if NewsPublisher can do that.<br><br>IDEAS for you.<br></p><ol><li>You can tell TinyMCE to manipulate any part of the DOM for you, that means you can fill out any hidden fields that comes with NewsPublisher. If you don\'t want modal windows, use accordion hide show.</li><li>You can create a menu button that shows the current user his/her published/drafted resources. Trust me, there is little you will not be able to accomplish.</li><li>If you like this stuff, donate to the cause.</li></ol></div>").appendTo("#twNpContainer");
      }
    },
    menu: [
      {
        text: \'Save Resource ...\',
        classes: "nppubButton",
        onclick: saveDetails
      }, {
        text: \'Exit Edit Mode\',
        classes: "npEditButton",
        onclick: function() {
          tinymce.get("tmpTempEditor").windowManager.confirm("You will lose any changes, exit?", function(s) {
            if (s) {
              // window.location.href = "[[~[[*id]]? &scheme=`full`]]";
              window.location.href = "[[~[[+cancelid]]? &scheme=`full`]]";
            } else {}
          });
        }
      }, {
        text: \'Toggle Fixed Toolbar\',
        classes: "npToggleButton",
        onclick: function() {
          if ($(".publishButtons #tinymceWrapperBubbleNP.fixedToolBar").length) {
            $(".publishButtons #tinymceWrapperBubbleNP.fixedToolBar").removeClass("fixedToolBar").insertBefore(".publishButtons");
            $(".publishButtons").css("width","")
          } else {
            $(".publishButtons").css("width","100%")
            $("#tinymceWrapperBubbleNP").addClass("fixedToolBar").appendTo(".publishButtons");
          }
        }
      }, {
        text: "Make Links Clickable",
        classes: "hyper",
        onclick: function() {
          if ($("[data-tiny]  a").length) {
            if ($(".mce-hyper:contains(\'Make Links Clickable\')").length > 0) {
              $("[data-tiny] a").attr("contenteditable", "false");
              $(".mce-hyper:contains(\'Make Links Clickable\')").text(\'Unpreview Links\')
              tinymce.get("tmpTempEditor").windowManager.alert("Retoggle for new links");
            } else if ($(".mce-hyper:contains(\'Unpreview Links\')").length > 0) {
              $("[data-tiny]  a").removeAttr("contenteditable");
              $(".mce-hyper:contains(\'Unpreview Links\')").text(\'Make Links Clickable\')
            }
          } else {
            tinymce.get("tmpTempEditor").windowManager.alert("Links do not exist");
            return;
          }
        }
      },
      {
        text: "Help",
        classes: "npHelpButton",
        onclick: function() {
          popNPfields("#twHelp", "DonShakespeare\'s Free 911 Help Line", \'\', \'\', "Got it?");
        }
      }
    ],
    // onSubmit: twNPpublishedSUBMIT
  }).renderTo(document.getElementsByClassName("publishButtons"));

}

function welcomeEachEditor(){
  var count = 0;
  if(typeof tinymce !=="undefined"){
    var editors = tinymce.get(), i, elm;
    for (i = 0; i < editors.length; i++) {
        elm = $(editors[i].targetElm).data("tiny");
      if(elm) {
      count++
      }
    }
  }
  return count;
}

$(document).ready(function(){
  (function(){
    var numberOfReTries = 10;
    var milSecondsToWaitToTry = 700;
    var tinyNumber = $("[data-tiny]").length;
    var gruntTries = 0;
    var tinyCheck = setInterval(function() {
      var error = function(raw){
        var rawText="";
        if(raw){
          var rawText ="\\n\\rAlso, we noticed that TinyMCE core did not load at all"
        }
        var text = "ERROR! ...after "+numberOfReTries+" tries of checking, we found that: \\n\\rNumber of elements with data-tiny attribute = "+tinyNumber+"\\n\\rNumber of TinyMCE instances = "+welcomeEachEditor()+"\\n\\rThis mismatch has rendered TinyMagicPublisher unusable.\\n\\rConsider refreshing this page if nothing else works."+rawText;
        return text;
      }
      gruntTries++;
      console.log("number of supported Editors present ..("+welcomeEachEditor()+")"); //debug stuff
      console.log("searching for Editors ..# of tries("+gruntTries+")"); //debug stuff
      if (welcomeEachEditor() == tinyNumber) {
        createButtons();
        clearInterval(tinyCheck);
        console.log("search is complete"); //debug stuff
      }
      else if(gruntTries > numberOfReTries){
        clearInterval(tinyCheck);
        if(typeof tinymce !=="undefined" && tinymce.editors.length > 0){
          setTimeout(function(){
            tinymce.get("tmpTempEditor").windowManager.alert(error())
          },500)
        }
        else{
          setTimeout(function(){
            alert(error("raw"));
          },500)
        }
        console.log("search is complete"); //debug stuff
      }
    }, milSecondsToWaitToTry);
  })();
})
',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'function popNPfields(target, title, width, height, okay) {
  target = $(target);
  var $inline = $(\'<div>\').hide().insertBefore(target);
  tinymce.get("tmpTempEditor").windowManager.open({
    title: title ? title : "Modal Window",
    width: 550,
    height: height ? height : $(target).height(),
    classes: "popNPfields",
    style: "padding:10px;",
    autoScroll: false,
    items: [{
      classes: "popNPfields",
      type: \'container\',
      onPostRender: function() {
        $(target).appendTo(".mce-popNPfields > .mce-container-body");
        $(".mce-popNPfields .mce-close").on("click", function(e) {
          $inline.replaceWith(target);
          tinymce.get("tmpTempEditor").windowManager.close();
          e.preventDefault;
        });
      }
    }],
    buttons: [{
      text: okay ? okay : "Okay",
      onclick: function() {
        $inline.replaceWith(target);
        tinymce.get("tmpTempEditor").windowManager.close();
      }
    }]
  });
}

function stateToggle() {
  var self = this;
  tinymce.each(tinymce.editors, function(editor) {
    var elm = $(editor.targetElm).data("tiny");
    if(elm) {
      editor.on(\'nodeChange\', function() {
        self.disabled(!editor.isDirty())
      });
    }
  });
}
function saveDetails(){
  //add as many fields as you like
  twNPhideFromMenu = twNPpublished = twNPpublishedby = twNPpublishedon = twNPpubDate = twNPunpubDate = twNPmenuTitle = twNPmenuIndex = twNPalias = twNPtags =  twNPrichtext = twNPlongTitle = twNPparentList = twNPtemplateList = twNPdeleted = twNPtemplateListSelVal = twNPparentListSelVal = \'\';
  if ($("#np-published").length) {
    twNPpublished = {
      label: \'Published\',
      type: \'checkbox\',
      autofocus: false,
      onchange: function(){
        var checkbox = document.getElementById("np-published");
        if(this.value()){
          checkbox.checked = true;
          checkbox.value = 1;
          $("input[name=published]").val(1);
        }
        else{
          checkbox.checked = false;
          checkbox.value = \'\';
          $("input[name=published]").val(\'\');
        }
      },
      onPostRender: function(){
        if(document.getElementById(\'np-published\').checked) {
          this.value(1);
        }
      }
    }
  }
  if ($("#np-richtext").length) {
    twNPrichtext = {
      label: \'Richtext\',
      type: \'checkbox\',
      autofocus: false,
      onchange: function(){
        var checkbox = document.getElementById("np-richtext");
        if(this.value()){
          checkbox.checked = true;
          checkbox.value = 1;
        }
        else{
          checkbox.checked = false;
          checkbox.value = \'\';
        }
      },
      onPostRender: function(){
        if(document.getElementById(\'np-richtext\').checked) {
          this.value(1);
        }
      }
    }
  }
  if($("#np-hidemenu").length){
    twNPhideFromMenu = {
      label: \'Hide from Menu\',
      type: \'checkbox\',
      onchange: function(){
        var checkbox = document.getElementById("np-hidemenu");
        if(this.value()){
          checkbox.checked = true;
          checkbox.value = 1;
        }
        else{
          checkbox.checked = false;
          checkbox.value = \'\';
        }
      },
      onPostRender: function(){
        if(document.getElementById(\'np-hidemenu\').checked) {
          this.value(1);
        }
      }
    }
  }
  if ($("#pub_date").length) {
    twNPpubDate = {
      type: \'textbox\',
      classes: \'pubDate\',
      tooltip: \'Publish Future Date: Y-M-D format\',
      label: \'Publish Date\',
      maxWidth: 100,
      onchange: function(){
        $("#pub_date").val(this.value())
      },
      onPostRender:function(){
        this.value($("#pub_date").val());
      }
    }
  }
  if ($("#publishedon").length) {
    twNPpublishedon = {
      type: \'textbox\',
      classes: \'publishedon\',
      tooltip: \'Published Date: Y-M-D format\',
      label: \'Published On\',
      maxWidth: 100,
      onchange: function(){
        $("#publishedon").val(this.value())
      },
      onPostRender:function(){
        this.value($("#publishedon").val());
      }
    }
  }
  if ($("#np-publishedby").length) {
    twNPpublishedby = {
      type: \'textbox\',
      classes: \'publishedby\',
      tooltip: \'Published By\',
      label: \'Published By\',
      maxWidth: 35,
      onchange: function(){
        $("#np-publishedby").val(this.value())
      },
      onPostRender:function(){
        this.value($("#np-publishedby").val());
      }
    }
  }
  if ($("#unpub_date").length) {
    twNPunpubDate = {
      type: \'textbox\',
      classes: \'unpubDate\',
      tooltip: \'Unpublish Date: Y-M-D format\',
      label: \'Unpublish Date\',
      maxWidth: 100,
      onchange: function(){
        $("#unpub_date").val(this.value())
      },
      onPostRender:function(){
        this.value($("#unpub_date").val());
      }
    }
  }
  if ($("#np-menutitle").length) {
    twNPmenuTitle = {
      type: \'textbox\',
      classes: \'menuTitle\',
      tooltip: \'Menu Title\',
      label: \'Menu Title\',
      onchange: function(){
        $("#np-menutitle").val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-menutitle").val());
      }
    }
  }
  if($(\'#np-existingTags option\').length){
    var maxWidthTags = 191;
    var tooltipsTags = \'All Tags are sanitized: no spaces, special xters etc. Use Drop Down to access Existing Tags from your site - duplicates will be removed upon selection\';
    var usedNames = {};
    $("#np-existingTags option").each(function () {
      if(usedNames[this.text]) {
          $(this).attr({\'title\':\'Duplicates are disabled\',\'disabled\':\'disabled\'});
      } else {
          usedNames[this.text] = this.value;
      }
    });
    $("#np-existingTags optgroup").each(function () {
      if(!$(this).children(\'option\').length){
        $(this).remove()
      }
    })
  }
  else{
    var maxWidthTags = \'\';
    var tooltipsTags = \'Resource Tags\';
  }
  if ($("#np-"+twTAGtvID).length) {
    twNPtags = {
      type: \'textbox\',
      classes: \'articlestags\',
      tooltip: tooltipsTags,
      label: \'Resource Tags\',
      maxWidth: maxWidthTags,
      onchange: function(){
        $("#np-"+twTAGtvID).val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-"+twTAGtvID).val());
        if($(\'#np-existingTags option\').length){
          this.value($("#np-"+twTAGtvID).val()
          // .replace(/[^-,_A-Za-z0-9]+/g, \'\')     // .replace(/ /g,\'\')
          .replace(/[^,A-Za-z0-9]+/g, \'\')
          .replace(/^[,\\s]+|[,\\s]+$/g, \'\')
          .replace(/,[,\\s]*,/g, \',\'));
          $(\'.mce-articlestags\').attr("placeholder", "Please enter valid Tags here...").css({\'padding-right\':\'25px\'}).parent().append( $(\'#np-existingTags\').clone().addClass(\'mce-np-existingTags\').css({\'position\': \'absolute\',\'top\':\'0\',\'bottom\':\'0\',\'margin\':\'auto\', \'right\':\'0\',\'width\':\'18px\'}) )

          $(\'.mce-np-existingTags option\')
          .each(function(){
              var optText = $(this).text()
              .replace(/[^,A-Za-z0-9]+/g, \'\');
              $(this).html(optText);
              $(this).val(optText);
            })
          $(\'.mce-np-existingTags\')
          .prop(\'selectedIndex\',-1)
          .on(\'change\',function(){
              var thisVal = $( ".mce-np-existingTags .twTagOptions:selected" ).val();
              var val = $(".mce-articlestags").val()
              .replace(/[^,A-Za-z0-9]+/g, \'\')
              .replace(/^[,\\s]+|[,\\s]+$/g, \'\')
              .replace(/,[,\\s]*,/g, \',\');
              var tags = val ? val.split(\',\') : [];
              var index = tags.indexOf(thisVal);
              if (index > -1) {
                tags.splice(index, 1);
              } else {
                tags.push(thisVal);
              }
              $("#np-"+twTAGtvID+", .mce-articlestags").val(tags.join(\',\'));
              // $(\'.mce-np-existingTags\').prop(\'selectedIndex\',-1);
              });
        }
      }
    }
  }
  if ($("#np-menuindex").length) {
    twNPmenuIndex = {
      type: \'textbox\',
      classes: \'menuindex\',
      tooltip: \'Menu Index\',
      label: \'Menu Index\',
      maxWidth: 35,
      onchange: function(){
        $("#np-menuindex").val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-menuindex").val());
      }
    }
  }
  if ($("#np-alias").length) {
    twNPalias = {
      type: \'textbox\',
      classes: \'alias\',
      tooltip: \'Resource Alias\',
      label: \'Resource Alias\',
      onchange: function(){
        $("#np-alias").val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-alias").val());
      }
    }
  }
  if ($("#np-longtitle").length) {
    twNPlongTitle = {
      type: \'textbox\',
      classes: \'longTitle\',
      tooltip: \'Long Title\',
      label: \'Long Title\',
      onchange: function(){
        $("#np-longtitle").val(this.value())
      },
      onPostRender: function() {
        this.value($("#np-longtitle").val());
      }
    }
  }
  if ($("#np-parent-container select option").length) {
    twNPparentListValues = [];
    $("#np-parent-container option").each(function(){
      var text = $(this).text();
      var val = $(this).val();
      if($(this).is(\':selected\')){
        twNPparentListSelVal = val;
      }
      twNPparentListValues.push(
        {
          text: text,
          value:val,
          onselect: function() {
            $("#np-parent-container select").val(this.value());
          }
        }
      )
    });
    if(twNPparentListSelVal){
      twNPparentList = {
        type: \'listbox\',
        classes: \'parentList\',
        values: twNPparentListValues,
        tooltip: \'Parent Resources\',
        label: \'Parent\',
        onPostRender:function(){
          this.value(twNPparentListSelVal);
        }
      }
    }
  }
  if ($("#np-template-container select option").length) {
    twNPtemplateListValues = [];
    $("#np-template-container option").each(function(){
      var text = $(this).text();
      var val = $(this).val();
      if($(this).is(\':selected\')){
        twNPtemplateListSelVal = val;
      }
      twNPtemplateListValues.push(
        {
          text: text,
          value:val,
          onselect: function() {
            $("#np-template-container select").val(this.value());
          }
        }
      )
    });
    if(twNPtemplateListSelVal){
      twNPtemplateList = {
        type: \'listbox\',
        classes: \'templateList\',
        values: twNPtemplateListValues,
        tooltip: \'Templates\',
        label: \'Template\',
        onPostRender:function(){
          this.value(twNPtemplateListSelVal);
        }
      }
    }
  }
  if ($("#np-deleted").length) {
    twNPdeleted = {
      type: \'checkbox\',
      classes: \'npDeleted\',
      // tooltip: \'Marks the resource for deletion\',
      label: \'Mark Resource For Deletion\',
      onchange: function(){
        var checkbox = document.getElementById("np-deleted");
        if(this.value()){
          checkbox.checked = true;
          checkbox.value = 1;
          var tthis = this;
          if(npCreate == 1){
            tinymce.get("tmpTempEditor").windowManager.confirm("Delete this resource immediately?", function(s) {
              if (s) {
                $("#newspublisherForm").submit();
              } else {
                checkbox.checked = false;
                checkbox.value = \'\';
                tthis.value(0);
              }
            });
          } else{
              tinymce.get("tmpTempEditor").windowManager.alert("Not applicable here", function(){tthis.value(0)});
          }
        }
        else{
          checkbox.checked = false;
          checkbox.value = \'\';
        }
      },
      onPostRender:function(){
        if(document.getElementById(\'np-deleted\').checked) {
          this.value(1);
        }
      }
    }
  }

  tinymce.get("tmpTempEditor").windowManager.open({
    title: \'MODX Resource Details\',
    classes: \'resourceDetails\',
    bodyType: \'tabpanel\',
    activateTab: 2,
    // width: 300,
    // minHeight: 255,
    autoScroll:true,
    onPostRender: function() {
      // $(".mce-resourceDetails .mce-close").remove()
      // $(".mce-resourceDetails .mce-foot .mce-widget *:not(.mce-saveMODX)").remove()
      // var thisAppendTo = $(".mce-foot > .mce-container-body.mce-abs-layout").attr(\'id\')
      // tinymce.ui.Factory.create({
      //   type: \'button\',
      // }).renderTo(document.getElementById(thisAppendTo));
    },
    body: [
    {
      title: "Published (by)",
      type: "form",
      items: [
        twNPpublished,
        twNPpublishedon,
        twNPpubDate,
        twNPunpubDate,
        twNPpublishedby
      ]
    },
    {
      title: "Resource Details",
      type: "form",
      items: [
        twNPtags,
        twNPalias,
        twNPlongTitle,
        twNPparentList,
        twNPtemplateList,
        twNPrichtext
      ]
    },
    {
      title: "Navigation",
      type: "form",
      items: [
        twNPhideFromMenu,
        twNPmenuTitle,
        twNPmenuIndex
      ]
    },
    {
      title: "Delete",
      style: \'color:red!important;\',
      type: "form",
      items: [
        twNPdeleted
      ]
    }
    ],
    buttons:[
    {
      text: \'Save Changes to MODX\',
      tooltip: \'This will Save to MODX all your changes\',
      classes: \'saveMODX\',
      active: true,
      autofocus: true,
      style: \'position:absolute;top:10px;left:8px;\',
      onclick:saveArticle
    }

    ]
  })
}

function saveArticle() {
  dontDoThat = 0;
  tinymce.each(tinymce.editors, function(editor) {
    var elm = $(editor.targetElm).data("tiny");
    if(elm) {
      var tinyName = editor.getParam("name");
      var more = "";
      if($(".mce-resourceDetails").length){
        var more = "Close the Resource Details modal window to see the field\\n\\r";
      }
      if ($(editor.getBody()).text().trim() == tinyName) {
        dontDoThat++;
        if(dontDoThat < 2){
          editor.windowManager.alert(tinyName + " (the field in focus) is not a valid input, please change\\n\\r"+more);
          }
      }
      else if (dontDoThat < 1 && $(editor.getBody()).text().trim() !== tinyName && !$(editor.getBody()).text().trim().length) {
        dontDoThat++;
        if(dontDoThat < 2){
          editor.windowManager.alert(tinyName + " (the field in focus) is empty, please fill\\n\\r"+more);
        }
      }
    }
  })
  if (dontDoThat < 1) {
    tinymce.get("tmpTempEditor").windowManager.confirm("Save resource?", function(s) {
      if (s) {
        tinymce.triggerSave();
        $("#newspublisherForm").submit();
      } else {}
    });
  }
}

function saveButton(editor) {
  if (!editor.isDirty()) {
    $(".mce-npPublishButton").removeClass("mce-active");
    $(\'.mce-npPublishButton i.mce-ico\').addClass(\'mce-i-fullpage\').removeClass(\'mce-i-save\')
    // $(".mce-npPublishButton, .mce-discardMODX").addClass("mce-disabled").attr("aria-disabled", true).children("button").attr("disabled", "disabled");
  } else {
    $(".mce-npPublishButton").addClass("mce-active");
    $(\'.mce-npPublishButton i.mce-ico\').addClass(\'mce-i-save\').removeClass(\'mce-i-fullpage\')
    // $(".mce-npPublishButton, .mce-discardMODX").fadeIn("slow").removeClass("mce-disabled").removeAttr("aria-disabled").children().removeAttr("disabled");
  }
  var pID = $("#" + editor.id).attr("data-tiny");
  if(editor.getParam("external_plugins",1).twExoticMarkdownEditor){
    $("#" + pID).val(saveMyFootMarks(editor.getContent({source:"true"})));
  }
  else{
    $("#" + pID).val(tinymce.activeEditor.getContent());
  }
}

function createButtons() {
  //common buttons + functions for each TinyMCE init, remove to override, 
  // var count =0; //debug stuff
  if(!$("#tmpTempEditor").length){
    $("body").append("<input id=tmpTempEditor type=hidden value=\'\' />");
    tinymce.init({
      selector: "#tmpTempEditor",
      skin_url: tinymce.activeEditor.getParam("skin_url", "[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles"),
      inline:true,
      forced_root_block : "",
      force_br_newlines : false,
      force_p_newlines : false
    })
  }
  tinymce.each(tinymce.editors, function(editor) {
    var elm = $(editor.targetElm).data("tiny");
    if(elm) {
      // console.log("number of legal Editor instances  .."+count++); //debug stuff
      // editor.on("keyup focus paste mouseup SetContent", function() {
      editor.on("change", function() {
        saveButton(editor);
      });
      editor.addMenuItem("reset", {
        title: "Cancel all changes!",
        text: "Discard changes",
        onPostRender: stateToggle,
        classes: \'discardMODX\',
        icon: "undo",
        onclick: function() {
          editor.windowManager.confirm("Really discard all changes?", function(s) {
            if (s) {
              $(editor.getBody()).html(editor.startContent)
            } else {}
          })
        }
      });
    }
  });
  //end common buttons
  tinymce.ui.Factory.create({
    type: \'menubutton\',
    tooltip: "Resource details ... other tasks",
    icon: "fullpage",
    classes: "npPublishButton",
    text: "PUBLISH ",
    onclick: function(){
      if(!$("form#newspublisherForm").length || !$(".fielderrormessage").length){
        npErrors();
        return false;
      }
    },
    onPostRender: function(){
      if (!$("#twHelp").length) {
        $("<div id=\'twHelp\' style=\'height:300px; white-space: normal !important;\'><p>Hi there.<br>I know you are enjoying this stuff, well, so am I.<br>You will see that it is easy to extend this frontend magic idea to suit your case.<br>I will offer premium support for anything surpassing 5 minutes of my precious time.<br><br>This project was inspired by my zeal to surpass <strong>medium.com</strong> and <strong>createPHP.</strong> Be thou the judge!<br>I know the next thing you want is AJAX, well, ask Bob Ray if NewsPublisher can do that.<br><br>IDEAS for you.<br></p><ol><li>You can tell TinyMCE to manipulate any part of the DOM for you, that means you can fill out any hidden fields that comes with NewsPublisher. If you don\'t want modal windows, use accordion hide show.</li><li>You can create a menu button that shows the current user his/her published/drafted resources. Trust me, there is little you will not be able to accomplish.</li><li>If you like this stuff, donate to the cause.</li></ol></div>").appendTo("#twNpContainer");
      }
    },
    menu: [
      {
        text: \'Save Resource ...\',
        classes: "nppubButton",
        onclick: saveDetails
      }, {
        text: \'Exit Edit Mode\',
        classes: "npEditButton",
        onclick: function() {
          tinymce.get("tmpTempEditor").windowManager.confirm("You will lose any changes, exit?", function(s) {
            if (s) {
              // window.location.href = "[[~[[*id]]? &scheme=`full`]]";
              window.location.href = "[[~[[+cancelid]]? &scheme=`full`]]";
            } else {}
          });
        }
      }, {
        text: \'Toggle Fixed Toolbar\',
        classes: "npToggleButton",
        onclick: function() {
          if ($(".publishButtons #tinymceWrapperBubbleNP.fixedToolBar").length) {
            $(".publishButtons #tinymceWrapperBubbleNP.fixedToolBar").removeClass("fixedToolBar").insertBefore(".publishButtons");
            $(".publishButtons").css("width","")
          } else {
            $(".publishButtons").css("width","100%")
            $("#tinymceWrapperBubbleNP").addClass("fixedToolBar").appendTo(".publishButtons");
          }
        }
      }, {
        text: "Make Links Clickable",
        classes: "hyper",
        onclick: function() {
          if ($("[data-tiny]  a").length) {
            if ($(".mce-hyper:contains(\'Make Links Clickable\')").length > 0) {
              $("[data-tiny] a").attr("contenteditable", "false");
              $(".mce-hyper:contains(\'Make Links Clickable\')").text(\'Unpreview Links\')
              tinymce.get("tmpTempEditor").windowManager.alert("Retoggle for new links");
            } else if ($(".mce-hyper:contains(\'Unpreview Links\')").length > 0) {
              $("[data-tiny]  a").removeAttr("contenteditable");
              $(".mce-hyper:contains(\'Unpreview Links\')").text(\'Make Links Clickable\')
            }
          } else {
            tinymce.get("tmpTempEditor").windowManager.alert("Links do not exist");
            return;
          }
        }
      },
      {
        text: "Help",
        classes: "npHelpButton",
        onclick: function() {
          popNPfields("#twHelp", "DonShakespeare\'s Free 911 Help Line", \'\', \'\', "Got it?");
        }
      }
    ],
    // onSubmit: twNPpublishedSUBMIT
  }).renderTo(document.getElementsByClassName("publishButtons"));

}

function welcomeEachEditor(){
  var count = 0;
  if(typeof tinymce !=="undefined"){
    var editors = tinymce.get(), i, elm;
    for (i = 0; i < editors.length; i++) {
        elm = $(editors[i].targetElm).data("tiny");
      if(elm) {
      count++
      }
    }
  }
  return count;
}

$(document).ready(function(){
  (function(){
    var numberOfReTries = 10;
    var milSecondsToWaitToTry = 700;
    var tinyNumber = $("[data-tiny]").length;
    var gruntTries = 0;
    var tinyCheck = setInterval(function() {
      var error = function(raw){
        var rawText="";
        if(raw){
          var rawText ="\\n\\rAlso, we noticed that TinyMCE core did not load at all"
        }
        var text = "ERROR! ...after "+numberOfReTries+" tries of checking, we found that: \\n\\rNumber of elements with data-tiny attribute = "+tinyNumber+"\\n\\rNumber of TinyMCE instances = "+welcomeEachEditor()+"\\n\\rThis mismatch has rendered TinyMagicPublisher unusable.\\n\\rConsider refreshing this page if nothing else works."+rawText;
        return text;
      }
      gruntTries++;
      console.log("number of supported Editors present ..("+welcomeEachEditor()+")"); //debug stuff
      console.log("searching for Editors ..# of tries("+gruntTries+")"); //debug stuff
      if (welcomeEachEditor() == tinyNumber) {
        createButtons();
        clearInterval(tinyCheck);
        console.log("search is complete"); //debug stuff
      }
      else if(gruntTries > numberOfReTries){
        clearInterval(tinyCheck);
        if(typeof tinymce !=="undefined" && tinymce.editors.length > 0){
          setTimeout(function(){
            tinymce.get("tmpTempEditor").windowManager.alert(error())
          },500)
        }
        else{
          setTimeout(function(){
            alert(error("raw"));
          },500)
        }
        console.log("search is complete"); //debug stuff
      }
    }, milSecondsToWaitToTry);
  })();
})
',
    ),
  ),
  'd8b3f7cfdf3ce8c2987225cc81db60bd' => 
  array (
    'criteria' => 
    array (
      'name' => 'twBROWSERnpFileTpl',
    ),
    'object' => 
    array (
      'id' => 17,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'twBROWSERnpFileTpl',
      'description' => 'Custom Tpl chunk for NewsPublisher file TVs',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => '    <div id="np-[[+npx.fieldName]]-container" class="np-file">
        [[+np.error_[[+npx.fieldName]]]]
        <label class="fieldlabel" for="np-[[+npx.fieldName]]" title="[[+npx.help]]">[[+npx.caption]]: </label>
        <input name="[[+npx.fieldName]]" class="file" id="np-[[+npx.fieldName]]" type="text"  value="[[+np.[[+npx.fieldName]]]]" />
        <button type="button" onclick="autoFileBrowser(\'np-[[+npx.fieldName]]\')">[[%np_launch_file_browser]]</button>
    </div>',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '    <div id="np-[[+npx.fieldName]]-container" class="np-file">
        [[+np.error_[[+npx.fieldName]]]]
        <label class="fieldlabel" for="np-[[+npx.fieldName]]" title="[[+npx.help]]">[[+npx.caption]]: </label>
        <input name="[[+npx.fieldName]]" class="file" id="np-[[+npx.fieldName]]" type="text"  value="[[+np.[[+npx.fieldName]]]]" />
        <button type="button" onclick="autoFileBrowser(\'np-[[+npx.fieldName]]\')">[[%np_launch_file_browser]]</button>
    </div>',
    ),
  ),
  '8c47c5b5303ff330ce4b442a210d5038' => 
  array (
    'criteria' => 
    array (
      'name' => 'twExistingTagsSelectTpl',
    ),
    'object' => 
    array (
      'id' => 18,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'twExistingTagsSelectTpl',
      'description' => 'The tagLister call called by getResources to build each OPTION of the optgroup 
This chunk calls twExistingTagsSelectOptionsTpl. 
This chunk is called by twExistingTags',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => '<optgroup label="[[+pagetitle]]">
[[tagLister? &tv=`TinymceWrapperTags` &tvDelimiter=`,` &sortBy=`tag` &sortDir=`ASC` &parents=`[[+id]]` &limit=`100` &tpl=`twExistingTagsSelectOptionsTpl` &target=`[[+id]]`]]
</optgroup>',
      'locked' => 0,
      'properties' => NULL,
      'static' => 0,
      'static_file' => '',
      'content' => '<optgroup label="[[+pagetitle]]">
[[tagLister? &tv=`TinymceWrapperTags` &tvDelimiter=`,` &sortBy=`tag` &sortDir=`ASC` &parents=`[[+id]]` &limit=`100` &tpl=`twExistingTagsSelectOptionsTpl` &target=`[[+id]]`]]
</optgroup>',
    ),
  ),
  '6c36a152de96ae031cd139e32d33526a' => 
  array (
    'criteria' => 
    array (
      'name' => 'twExistingTagsSelectOptionsTpl',
    ),
    'object' => 
    array (
      'id' => 19,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'twExistingTagsSelectOptionsTpl',
      'description' => 'The tpl for each row of option called by tagLister (twExistingTagsSelectTpl)',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => '<option class="twTagOptions" value="[[+tag]]">[[+tag]]</option>',
      'locked' => 0,
      'properties' => NULL,
      'static' => 0,
      'static_file' => '',
      'content' => '<option class="twTagOptions" value="[[+tag]]">[[+tag]]</option>',
    ),
  ),
  'd9c394bae0c8d72ae5f924cb48d25e4b' => 
  array (
    'criteria' => 
    array (
      'name' => 'twExistingTags',
    ),
    'object' => 
    array (
      'id' => 20,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'twExistingTags',
      'description' => 'The main chunk (getResources) that builds the SELECT list of tags. This chunk calls twExistingTagsSelectTpl which in turn calls twExistingTagsSelectOptionsTpl
Do not remove the id="np-existingTags" !!!!!',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => '<select id="np-existingTags">[[getResources? &parents=`0` &depth=`10` &showHidden=`1` &showUnpublished=`1` &tpl=`twExistingTagsSelectTpl` &limit=`100` &sortby=`pagetitle` &sortdir=`ASC` ]]</select>',
      'locked' => 0,
      'properties' => NULL,
      'static' => 0,
      'static_file' => '',
      'content' => '<select id="np-existingTags">[[getResources? &parents=`0` &depth=`10` &showHidden=`1` &showUnpublished=`1` &tpl=`twExistingTagsSelectTpl` &limit=`100` &sortby=`pagetitle` &sortdir=`ASC` ]]</select>',
    ),
  ),
  '8290ef56bdac97e1fdac106fbc159257' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinyMagicPublisher',
    ),
    'object' => 
    array (
      'id' => 7,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinyMagicPublisher',
      'description' => 'Better to customize by doing [[!TinyMagicPublisher? &thisProperty=`myCustomValue`]] or [[!TinyMagicPublisher@customPropertySet]]. If you leave this snippet alone, you will have no need to back it up, or need to create custom PropertySet.',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => '/*TinymceWrapper TinyMagicPublisher Snippet transforms any textareas that NewsPublisher provides.
It can manipulate any field that is hidden and restore the values right back to DOM origin

-------------------Roadmap:
-Create more beautiful themes;
-Create more beautiful plugins;
-Look for more ways to be awesome;
---------------------------

Use freely, report freely, enjoy freely
Dedicated to those who have cried
---------------------------

http://www.leofec.com/modx-revolution/
-donshakespeare in the MODx forum
*/

//TinyMagicPublisherModifier Snippet
//empty value gets default_text for both new and existing docs
/*if (isset($_GET[\'edit\']) && $_GET[\'edit\'] == \'true\' || isset($_GET[\'create\']) && $_GET[\'create\'] == \'true\' ) {
  if($input){
    if ($modx->resource->get(\'createdby\') == $modx->user->get(\'id\') ) {
      $legalOWNER = 1;
    }
    if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') && !$modx->hasPermission(\'new_document\') ) {
      return;
    }
    $typesOfResourcesFields = array("pagetitle","alias","menutitle","longtitle","introtext","description","content");
    if(in_array($name, $typesOfResourcesFields)) {
      if($_POST[$name]){
        $name = $_POST[$name];
      }
      else{
        $name = $modx->resource->get($name);
      }
    }
    else{
      if($_POST[$name]){
        if($_POST[$name] == \'\'){
          $name = \'\';
        }
        $name = $_POST[$name];
      }
      else{
        $name = $modx->resource->getTVValue($name);
      }
    }
    $name = str_replace(array(\'[[\',\']]\'),array(\'{{\',\'}}\'),$name);
    return $name;
  }
}*/
function listArray($thisList){
  $thisList = preg_replace(\'/\\s+/\', \'\', $thisList);
  $thisList = preg_replace("/[^a-z0-9,-_]+/i", \' \', $thisList);
  $thisList = explode(\',\', $thisList);
  return $thisList;
}
function cssANDjs($mainCSSfile,$mainCSS,$jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions, $TinyJSONGalleryJS){
  global $modx;
  //register CSS and JS to browser for MAGIC mode
  if($mainCSSfile){
    $modx->regClientCSS($mainCSSfile);
  }
  else{
    $modx->regClientStartupHTMLBlock($mainCSS);
  }
  if($jQueryCDN){
    $modx->regClientScript($jQueryCDN);
  }
  $modx->regClientScript($tinymceCDN);
  $modx->regClientHTMLBlock($tinyInitsANDfunctions);
  if($TinyJSONGalleryJS){
    $modx->regClientScript($TinyJSONGalleryJS);
  }
}
function cssANDjsplain($jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions,$TinyJSONGalleryJS){
  global $modx;
  //register CSS and JS to browser for MAGIC mode
  if($jQueryCDN){
    $modx->regClientScript($jQueryCDN);
  }
  $modx->regClientScript($tinymceCDN);
  $modx->regClientHTMLBlock($tinyInitsANDfunctions);
  if($TinyJSONGalleryJS){
    $modx->regClientScript($TinyJSONGalleryJS);
  }
}
function redirectTo($desti,$param){
  global $modx;
  $url = $modx->makeUrl($desti,\'\',$param,\'full\');
  $modx->sendRedirect($url);
}
//let\'s set up some restrictions
if (isset($_GET[\'edit\']) && !isset($_GET[\'create\']) && $modx->resource->get(\'id\') !== $npCreateResource ) {
  $legalEDITparam = 1;
}
if (isset($_GET[\'create\']) && !isset($_GET[\'edit\']) && $modx->resource->get(\'id\') == $npCreateResource ) {
  $legalCREATEparam = 1;
}
if (!$modx->hasPermission(\'new_document\') || $_GET[\'create\'] !== \'true\') {
  $legalHASpermission = 1;
}
if ($modx->resource->get(\'createdby\') == $modx->user->get(\'id\') || $modx->user->isMember(listArray($editorAdminGroups))) {
  $legalOWNER = 1;
}
if($npPureMagicNonTraditional){
  //force disable a few NP calls
  $deletebutton = $duplicatebutton = 0;
  //never edit the (create) page - from which new resources will be created
  if($modx->resource->get(\'id\') == $npCreateResource && isset($_GET[\'edit\'])) {
    redirectTo($npCancelCreateId,\'\');
  }
  //never load the (create) page without the \'create\' url parameter
  if($modx->resource->get(\'id\') == $npCreateResource && !isset($_GET[\'create\']) ) {
    redirectTo($npCreateResource,\'create=true\');
  }
  //do not show \'edit\' or \'create\' buttons or load NewsPublisher on any pages specified
  if($npNoShow){
    $npNoShow = listarray($npNoShow);
    $rId = $modx->resource->get(\'id\');
    if(in_array((string)$rId, $npNoShow, true)) {
     return;
    }
  }
  //force a few NP settings
  $rtsummary = $rtcontent = $usetabs = 0;
  $which_editor = \'\';
  $richtext = \'No\';

  //do not show \'edit\' or \'create\' buttons or load Newspublisher on children with specified parentid
  if($npNoShowChildOfParents){
    $npNoShowChildOfParents = listarray($npNoShowChildOfParents);
    $parentId = $modx->resource->get(\'parent\');
    if(in_array((string)$parentId, $npNoShowChildOfParents, true)) {
     return;
    }
  }

  //superficially show or hide NewsPublisher form on client when debug status changes
  $npHide = \'style="display:none !important;"\';
  if($npDebug == 1) {
     $cssfile = \'\';
     $npHide = \'style="display:block !important;"\';
  }

  //use custom NewsPublisher outertpl to ensure <form action> url has the correct create/edit parameter
  if($modx->resource->get(\'id\') == $npCreateResource){
    $outertpl = $npCreateOuterTpl;
  }
  else{
    $outertpl = $npEditOuterTpl;
  }

  //quickly set variable to be pushed over to JS to see if to update resource with NewspUblisher content //now replaced by TMPModifier
  $npCreate = "\'\'";
  if (isset($_GET[\'edit\']) && $_GET[\'edit\'] == \'true\' && !isset($_GET[\'create\']) && $modx->resource->get(\'id\') !== $npCreateResource ) {
    $npCreate = 1;
  }

  //prepare the top bar buttons
  //no need for chunkSuffix, since calls to this are in template (@psets)
  if($jsTopBarButtonsTpl){
    if($npCreate == 1){
      $cancelHREF = $modx->resource->get(\'id\');
    }
    else{
      $cancelHREF = $npCancelCreateId;

    }
    $topBar = $modx->getChunk($jsTopBarButtonsTpl, array("cancelid" => $cancelHREF));
  }

  $errorJSandContentRefresh = \'
    var npTWerrTime;
    function npErrors(){
      clearTimeout(npTWerrTime);
      if (!$("#newspublisherForm").length) {
         $(".errorMessagesNpTw").fadeIn().html("NewsPublisher did not load: is it installed?");
      }
      if ($(".newspublisher > .errormessage").length) {
        // var err = $(".newspublisher").clone().children().remove().end().text();
        $(".errorMessagesNpTw").html("");
         $(".newspublisher > .errormessage").each(function(i, obj) {
            $(".errorMessagesNpTw").fadeIn().html($(".errorMessagesNpTw").html()+$(this).text()+"<br>");
         });
      }
      if ($(".fielderrormessage").length) {
        $(".errorMessagesNpTw").html("");
         $(".fielderrormessage").each(function(i, obj) {
            $(".errorMessagesNpTw").fadeIn().html($(".errorMessagesNpTw").html()+$(this).text()+"<br>");
         });

         // $("[data-tiny]").each(function(){
         //  npStuff = $(this).attr("data-tiny");
         //  npStuffVal = $("#"+npStuff).val();
         //  $(this).html(npStuffVal);
         //  })
      }
       npTWerrTime = setTimeout(function(){
         $(".errorMessagesNpTw").slideUp();
       },8500);
    }
    npErrors();
    npCreate = \'.$npCreate.\';
    // if(npCreate == 1){
    //    $("[data-tiny]").each(function(){
    //     npStuff = $(this).attr("data-tiny");
    //     npStuffVal = $("#"+npStuff).val();
    //     $(this).html(npStuffVal);
    //     })
    // }
    $("[data-tiny]").addClass("twNPactive")
  \';

  //get chunks according to NewsPublisher &show=`pagetitle,content,TV1`
  //this allows user to use custom JS (especially TinyMCE inits only when needed) for each textfield that will be reporting back to NewsPublisher on the client side.

  $commonTinyMCECode = $modx->getChunk(\'TinymceWrapperNPCommonCode\'.$chunkSuffix);
  $commonTinyMCECode = $commonTinyMCECode ? $commonTinyMCECode : \'\';
  $getTWchunks = "";
  $npshow = listarray($show);
  $i = 0;
  foreach ($npshow as $s) {
    $npshow[$i] = $modx->getChunk(\'TinymceWrapperNP\'.$s.$chunkSuffix, array(\'commonTinyMCECode\'=>$commonTinyMCECode));
    $getTWchunks.= $npshow[$i];
  }
}
else{
  $topBar = $errorJSandContentRefresh = \'\'; 
  if($npTraditionalTinyChunk){
    $getTWchunks = $modx->getChunk($npTraditionalTinyChunk);
  }
}


//let\'s setup some functions and file select callbacks for our supported file browsers
switch ($autoFileBrowser) {
  case \'elFinderBrowser\':
    $browserRTEurl = $elFinderBrowserRTEurl;
    $browserRTEtitle = $elFinderBrowserRTEtitle;
    break;
  case \'responsivefilemanagerBrowser\':
    $browserRTEurl = $responsivefilemanagerBrowserRTEurl;
    $browserRTEtitle = $responsivefilemanagerBrowserRTEtitle;
    break;
  case \'roxyFilemanBrowser\':
    $browserRTEurl = $roxyFilemanBrowserRTEurl;
    $browserRTEtitle = $roxyFilemanBrowserRTEtitle;
    break;
}
if ($autoFileBrowser == \'responsivefilemanagerBrowser\') {
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win, image) {
      myImage = image;
      resp = "\'.$browserRTEurl.\'";
      if (resp.indexOf("?") < 0) {
        resp += "?field_id=" + field_name;
      }
      else {
        resp += "&field_id=" + field_name;
      }
      tinyMCE.activeEditor.windowManager.open({
        // console.log(resp);
        title: "\'.$browserRTEtitle.\'",
        url: resp,
        width : window.innerWidth / 1.2,
        height : window.innerHeight /1.2
      }, {
        oninsert: function(url) {
         if(field_name.indexOf("np-") > -1){
          document.getElementById(field_name).value = url;
          tinyMCE.activeEditor.windowManager.close();
         }
         else{
          win.document.getElementById(field_name).value = url;
         }
        }
      });
    return false;
    }
    function responsive_filemanager_callback(field_id){
      tinyMCE.activeEditor.windowManager.close();
      if(myImage){
        var url=$("#"+field_id).val();
        $("[data-tiny-image="+field_id+"]").attr("src",url);
        tinyMCE.activeEditor.windowManager.alert("Image updated");
     }
    }
  \';
}
elseif ($autoFileBrowser == \'roxyFilemanBrowser\') {
  $browserFunctions=\'
    autoFileBrowser = \'.$autoFileBrowser.\';
      function \'.$autoFileBrowser.\'(field_name, url, type, win, image) {
        myImage = image;
        roxyFileman = "\'.$browserRTEurl.\'";
        if (roxyFileman.indexOf("?") < 0) {
          roxyFileman += "?type=" + type;
        }
        else {
          roxyFileman += "&type=" + type;
        }
        if(field_name.indexOf("np-") > -1){
          roxyFileman += "&input=" + field_name + "&value=" + document.getElementById(field_name).value;
        }
        else{
          roxyFileman += "&input=" + field_name + "&value=" + win.document.getElementById(field_name).value;
        }
        if(tinyMCE.activeEditor.settings.language){
          roxyFileman += "&langCode=" + tinyMCE.activeEditor.settings.language;
        }
        if(myImage){
           fieldValue = document.getElementById(field_name);
           var oldFieldValue = fieldValue.value;
           var checkChange = setInterval(function() {
            if(fieldValue.value != oldFieldValue) {
                $(fieldValue).trigger("change");
                $("[data-tiny-image="+field_name+"]").attr("src",fieldValue.value);
                tinyMCE.activeEditor.windowManager.alert("Image updated");
                clearInterval(checkChange);
            }
           },500);
        }
        tinyMCE.activeEditor.windowManager.open({
          title: "\'.$browserRTEtitle.\'",
          url: roxyFileman,
          plugins: "media",
          width : window.innerWidth / 1.2,
          height : window.innerHeight / 1.2
        },
        {
         onclose:function(){
          alert("closed")
         }
        });
      return false;
      }
  \';
}
else{
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win, image) {
      myImage = image;
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: "\'.$browserRTEurl.\'",
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2
      }, {
        oninsert: function(url) {
         if(myImage){
            document.getElementById(field_name).value = url;
            $("[data-tiny-image="+field_name+"]").attr("src",url);
            tinyMCE.activeEditor.windowManager.alert("Image updated");
         }
         else{
          if(win){
            win.document.getElementById(field_name).value = url;
          }
          else{
            document.getElementById(field_name).value = url;
          }
         }
        }
      });
    return false;
    }
  \';
}


//prepare NewsPublisher snippet call with all possible default settings
$sD = \'System Default\';

//runSnippet (which used to cause session problems)
 $newsPublisher = \'
[[!NewsPublisher? &activetab=`\'.$activetab.\'` &badwords=`\'.$badwords.\'` &allowedtags=`\'.$allowedtags.\'` &captions=`\'.$captions.\'` &clearcache=`\'.$clearcache.\'` &contentcols=`\'.$clearcache.\'` &contentrows=`\'.$clearcache.\'` &cssfile=`\'.$cssfile.\'` &groups=`\'.$groups.\'` &hoverhelp=`\'.$hoverhelp.\'` &initdatepicker=`\'.$initdatepicker.\'` &initrte=`\'.$initrte.\'` &intmaxlength=`\'.$intmaxlength.\'` &language=`\'.$language.\'` &listboxmax=`\'.$listboxmax.\'` &multiplelistboxmax=`\'.$multiplelistboxmax.\'` &parents=`\'.$parents.\'` &postid=`\'.$postid.\'` &prefix=`\'.$prefix.\'` &readonly=`\'.$readonly.\'` &required=`\'.$required.\'` &rtcontent=`\'.$rtcontent.\' `&rtsummary=`\'.$rtsummary.\'` &show=`\'.$show.\'` &stopOnBadTv=`\'.$stopOnBadTv.\'` &summarycols=`\'.$summarycols.\'` &summaryrows=`\'.$summaryrows.\'` &tabs=`\'.$tabs.\'` &templates=`\'.$templates.\'` &textmaxlength=`\'.$textmaxlength.\'` &tinyheight=`\'.$tinyheight.\'` &tinywidth=`\'.$tinywidth.\'` &usetabs=`\'.$usetabs.\'` &which_editor=`\'.$which_editor.\'` &aliasdateformat=`\'.$aliasdateformat.\'` &aliasprefix=`\'.$aliasprefix.\'` &aliastitle=`\'.$aliastitle.\'` &cacheable=`\'.$cacheable.\'` &classkey=`\'.$classkey.\'` &hidemenu=`\'.$hidemenu.\'` &parentid=`\'.$parentid.\'` &published=`\'.$published.\'` &richtext=`\'.$richtext.\'` &searchable=`\'.$searchable.\'` &template=`\'.$template.\'` &templateid=`\'.$templateid.\'` &booltpl=`\'.$booltpl.\'` &datetpl=`\'.$datetpl.\'` &errortpl=`\'.$errortpl.\'` &fielderrortpl=`\'.$fielderrortpl.\'` &inttpl=`\'.$inttpl.\'` &listoptiontpl=`\'.$listoptiontpl.\'` &listoutertpl=`\'.$listoutertpl.\'` &optionoutertpl=`\'.$optionoutertpl.\'` &optiontpl=`\'.$optiontpl.\'` &outertpl=`\'.$outertpl.\'` &texttpl=`\'.$texttpl.\'` &textareatpl=`\'.$textareatpl.\'` &cancelid=`\'.$cancelid.\'` &activetabs=`\'.$activetabs.\'` &shownotify=`\'.$shownotify.\'` &notifyChecked=`\'.$notifyChecked.\'` &editHome=`\'.$editHome.\'` &imagetpl=`\'.$imagetpl.\'` &filetpl=`\'.$filetpl.\'` &duplicatebutton=`\'.($duplicatebutton ?: 0).\'` &deletebutton=`\'.($deletebutton ?: 0).\'` &confirmdelete=`\'.($confirmdelete ?: 0).\'` &published_default=`\'.($published_default ? $published_default : $sD).\'` &hidemenu_default=`\'.($hidemenu_default ? $hidemenu_default : $sD).\'` &cacheable_default=`\'.($cacheable_default ? $cacheable_default : $sD).\'` &searchable_default=`\'.($searchable_default ? $searchable_default : $sD).\'` &richtext_default=`\'.($richtext_default ? $richtext_default : $sD).\'`]]
 \';
// $newsPublisher = $modx->runSnippet(\'NewsPublisher\',
//   array(
//   \'activetab\'=>$activetab,
//   \'badwords\'=>$badwords,
//   \'allowedtags\'=>$allowedtags,
//   \'captions\'=>$captions,
//   \'clearcache\'=>$clearcache,
//   \'contentcols\'=>$clearcache,
//   \'contentrows\'=>$clearcache,
//   \'cssfile\'=>$cssfile,
//   \'groups\'=>$groups,
//   \'hoverhelp\'=>$hoverhelp,
//   \'initdatepicker\'=>$initdatepicker,
//   \'initrte\'=>$initrte,
//   \'intmaxlength\'=>$intmaxlength,
//   \'language\'=>$language,
//   \'listboxmax\'=>$listboxmax,
//   \'multiplelistboxmax\'=>$multiplelistboxmax,
//   \'parents\'=>$parents,
//   \'postid\'=>$postid,
//   \'prefix\'=>$prefix,
//   \'readonly\'=>$readonly,
//   \'required\'=>$required,
//   \'rtcontent\'=>$rtcontent,
//   \'rtsummary\'=>$rtsummary,
//   \'show\'=>$show,
//   \'stopOnBadTv\'=>$stopOnBadTv,
//   \'summarycols\'=>$summarycols,
//   \'summaryrows\'=>$summaryrows,
//   \'tabs\'=>$tabs,
//   \'templates\'=>$templates,
//   \'textmaxlength\'=>$textmaxlength,
//   \'tinyheight\'=>$tinyheight,
//   \'tinywidth\'=>$tinywidth,
//   \'usetabs\'=>$usetabs,
//   \'which_editor\'=>$which_editor,
//   \'aliasdateformat\'=>$aliasdateformat,
//   \'aliasprefix\'=>$aliasprefix,
//   \'aliastitle\'=>$aliastitle,
//   \'cacheable\'=>$cacheable,
//   \'classkey\'=>$classkey,
//   \'hidemenu\'=>$hidemenu,
//   \'parentid\'=>$parentid,
//   \'published\'=>$published,
//   \'richtext\'=>$richtext,
//   \'searchable\'=>$searchable,
//   \'template\'=>$template,
//   \'templateid\'=>$templateid,
//   \'booltpl\'=>$booltpl,
//   \'datetpl\'=>$datetpl,
//   \'errortpl\'=>$errortpl,
//   \'fielderrortpl\'=>$fielderrortpl,
//   \'inttpl\'=>$inttpl,
//   \'listoutertpl\'=>$listouterpl,
//   \'listoptiontpl\'=>$listoptiontpl,
//   \'optionoutertpl\'=>$optionoutertpl,
//   \'optiontpl\'=>$optiontpl,
//   \'outertpl\'=>$outertpl,
//   \'texttpl\'=>$texttpl,
//   \'textareatpl\'=>$textareatpl,
//   \'cancelid\'=>$cancelid,
//   \'activetabs\'=>$activetabs,
//   \'shownotify\'=>$shownotify,
//   \'notifyChecked\'=>$notifyChecked,
//   \'editHome\'=>$editHome,
//   \'imagetpl\'=>$imagetpl,
//   \'filetpl\'=>$filetpl,
//   \'duplicatebutton\'=>($duplicatebutton ?: 0),
//   \'deletebutton\'=>($deletebutton ?: 0),
//   \'confirmdelete\'=>($confirmdelete ?: 0),
//   \'published_default\'=>($published_default ? $published_default : $sD),
//   \'hidemenu_default\'=>($hidemenu_default ? $hidemenu_default : $sD),
//   \'cacheable_default\'=>($cacheable_default ? $cacheable_default : $sD),
//   \'searchable_default\'=>($searchable_default ? $searchable_default : $sD),
//   \'richtext_default\'=>($richtext_default ? $richtext_default : $sD)
//   )
// ) ? : \'\';

//grab tag TV name
$twTagsTV = $twTagsTV ? : \'doesNotExist\';
//get possible getResource call that builds the <select> Tags site-wide
$twExistingTagsChunk = $modx->getChunk($twExistingTagsChunk);

//prepare TinyMCE for the frontend
$tinyInitsANDfunctions = \'
  <script type="text/javascript">
    twTAGtvID = "\'.$twTagsTV.\'";
    modxGalleryAssetsUrl = "\'.$modxGalleryAssetsUrl.\'";
    galleryBackUpRTEskin  = "\'.$galleryBackUpRTEskin .\'";
    \'.$errorJSandContentRefresh.\'
    \'.$browserFunctions.\'
    \'.$topBar.\'
    $(document).ready(function() {
      \'.$getTWchunks.\'
      });
  </script>
 \';

//prepare buttons and final NP call to be echoed
$buttonsANDnewspublisher  = \'<div id="tinymceWrapperBubbleNP"></div><div class="publishButtons"></div>\'.$npErrorMessages.\'<div id="twNpContainer" \'.$npHide.\'>\'.$twExistingTagsChunk.$newsPublisher.\'</div>\';

//do the plain ol\' NP
if($npPureMagicNonTraditional == 0){
  cssANDjsplain($jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions,$TinyJSONGalleryJS);
  echo $newsPublisher;
}
//supercharge the plain ol\' NP
else{
  //reset these session variables so that NewsPublisher can have clean slate for Editing existing resource or creating a new one
  if(isset($_SESSION[\'np_doc_id\'], $_SESSION[\'np_doc_to_edit\'])) {
    unset($_SESSION[\'np_doc_id\'], $_SESSION[\'np_doc_to_edit\']);
  }

  //grab url parameters and init NewsPublisher accordingly
  if ($legalEDITparam == 1) {
    //return to resource url withou the edit parameter
    if ($_GET[\'edit\'] !== \'true\' || !$legalOWNER == 1) {
      redirectTo($modx->resource->get(\'id\'),\'\');
    }
    //restrict editing to owner of resource or Admin editors
    if ($legalOWNER == 1) {
      //give NewsPublisher snippet some food
      $_SESSION[\'np_doc_to_edit\'] = $modx->resource->get(\'id\');
      $_SESSION[\'np_doc_id\'] = $modx->resource->get(\'id\');

      //register CSS and JS to browser
      cssANDjs($mainCSSfile,$mainCSS,$jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions,$TinyJSONGalleryJS);
      echo $buttonsANDnewspublisher;
    }
  }
  elseif ($legalCREATEparam == 1) {
    if ($legalHASpermission) {
      redirectTo($npCancelCreateId,\'\');
    }

    //register CSS and JS to browser
    cssANDjs($mainCSSfile,$mainCSS,$jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions,$TinyJSONGalleryJS);
    echo $buttonsANDnewspublisher;
  }
  else{
    if($createNewButton){
      $createNewButton = \'<a href="[[~\'.$npCreateResource.\' ? &create=`true` &scheme=`full`]]">\'.$createNewButton.\'</a>\';
    }
    else{
      $createNewButton = \'\';
    }
    if($editThisButton){
      $editThisButton = \'<a href="[[~[[*id]]? &edit=`true` &scheme=`full`]]">\'.$editThisButton.\'</a>\';
    }
    else{
      $editThisButton = \'\';
    }
    if($editInManagerButton){
      $editInManagerButton = \'<a href="\'.MODX_MANAGER_URL.\'?a=resource/update&id=\'.$modx->resource->get("id").\'">\'.$editInManagerButton.\'</a>\';
    }
    else{
      $editInManagerButton = \'\';
    }
    if (!$modx->hasPermission(\'new_document\')) {
      $createNewButton = \'\';
    }
    if (!$modx->user->hasSessionContext(\'mgr\') && !$modx->hasPermission(\'edit_document\') && !$legalOWNER) {
      $editInManagerButton = \'\';
    }
    if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') ) {
      $editThisButton = \'\';
    }
    // if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') && !$modx->hasPermission(\'new_document\') ) {
    if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') && !$modx->hasPermission(\'new_document\') ) {
      return;
    }
    $editCreateButtons = \'<div class="publishButtons">\'.$editThisButton.$createNewButton.$editInManagerButton.\'</div>\';
    echo $editCreateButtons;
    $modx->regClientStartupHTMLBlock($publishButtonCSS);
  }
}',
      'locked' => 0,
      'properties' => 'a:91:{s:25:"npPureMagicNonTraditional";a:7:{s:4:"name";s:25:"npPureMagicNonTraditional";s:4:"desc";s:186:"Set to YES to use the full features of this Extra with ultra modern contenteditable sweetness. Set to NO to use your old boring existing setup with TinymceWrapper chunks in the frontend.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:3:"Yes";s:7:"lexicon";N;s:4:"area";s:11:"00 Activate";}s:22:"npTraditionalTinyChunk";a:7:{s:4:"name";s:22:"npTraditionalTinyChunk";s:4:"desc";s:246:"&npPureMagicNonTraditional must be turned off for this to work. Specify the name of chunk you wish to use to house all your frontend TinyMCE code. For backup &chunkSuffix will not affect this, just put in any name you want - of an existing chunk.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:27:"TinymceWrapperNPtraditional";s:7:"lexicon";N;s:4:"area";s:11:"00 Activate";}s:23:"elFinderBrowserRTEtitle";a:7:{s:4:"name";s:23:"elFinderBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:24:"elFinder Awesome Browser";s:7:"lexicon";N;s:4:"area";s:11:"00 Browsers";}s:21:"elFinderBrowserRTEurl";a:7:{s:4:"name";s:21:"elFinderBrowserRTEurl";s:4:"desc";s:71:"Supply valid url in [[!TinyMagicPublisher? ...]] or custom property set";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:11:"00 Browsers";}s:36:"responsivefilemanagerBrowserRTEtitle";a:7:{s:4:"name";s:36:"responsivefilemanagerBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:22:"Responsive FileManager";s:7:"lexicon";N;s:4:"area";s:11:"00 Browsers";}s:34:"responsivefilemanagerBrowserRTEurl";a:7:{s:4:"name";s:34:"responsivefilemanagerBrowserRTEurl";s:4:"desc";s:71:"Supply valid url in [[!TinyMagicPublisher? ...]] or custom property set";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:11:"00 Browsers";}s:26:"roxyFilemanBrowserRTEtitle";a:7:{s:4:"name";s:26:"roxyFilemanBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"Roxy Fileman";s:7:"lexicon";N;s:4:"area";s:11:"00 Browsers";}s:24:"roxyFilemanBrowserRTEurl";a:7:{s:4:"name";s:24:"roxyFilemanBrowserRTEurl";s:4:"desc";s:71:"Supply valid url in [[!TinyMagicPublisher? ...]] or custom property set";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:11:"00 Browsers";}s:17:"TinyJSONGalleryJS";a:7:{s:4:"name";s:17:"TinyJSONGalleryJS";s:4:"desc";s:29:"The url to TinyJSONGallery.js";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:15:"autoFileBrowser";a:7:{s:4:"name";s:15:"autoFileBrowser";s:4:"desc";s:109:"Which custom file browser to use in the frontend? MODx manager file browser is not supported for safe reasons";s:4:"type";s:4:"list";s:7:"options";a:3:{i:0;a:2:{s:4:"text";s:15:"elFinderBrowser";s:5:"value";s:15:"elFinderBrowser";}i:1;a:2:{s:4:"text";s:28:"responsivefilemanagerBrowser";s:5:"value";s:28:"responsivefilemanagerBrowser";}i:2;a:2:{s:4:"text";s:18:"roxyFilemanBrowser";s:5:"value";s:18:"roxyFilemanBrowser";}}s:5:"value";s:15:"elFinderBrowser";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:18:"cancelCreateButton";a:7:{s:4:"name";s:18:"cancelCreateButton";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Cancel Create";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:16:"cancelEditButton";a:7:{s:4:"name";s:16:"cancelEditButton";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"Cancel";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:11:"chunkSuffix";a:7:{s:4:"name";s:11:"chunkSuffix";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:15:"createNewButton";a:7:{s:4:"name";s:15:"createNewButton";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"New Resource";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:19:"editInManagerButton";a:7:{s:4:"name";s:19:"editInManagerButton";s:4:"desc";s:136:"Show a button to edit this resource not in the frontend but rather in the MODX Manager backend. Requires the user to have mgr permission";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:15:"Edit in Manager";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:14:"editThisButton";a:7:{s:4:"name";s:14:"editThisButton";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:14:"Edit This Page";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:17:"editorAdminGroups";a:7:{s:4:"name";s:17:"editorAdminGroups";s:4:"desc";s:256:"Comma-separated list of admin groups that can edit any resource.......By default, the NP edit button/TinyMCE will show if the user browsing is also the one who created the resource. But sometimes you want an Admin group(s) to be able to edit other content.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:13:"Administrator";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:20:"galleryBackUpRTEskin";a:7:{s:4:"name";s:20:"galleryBackUpRTEskin";s:4:"desc";s:139:"If you don\'t have a skin already loaded on the page, this link will prevent troubles ... make sure it is uniform with the RTEs on your page";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:9:"jQueryCDN";a:7:{s:4:"name";s:9:"jQueryCDN";s:4:"desc";s:45:"Leave empty if you have jQuery loaded already";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:43:"https://code.jquery.com/jquery-2.1.3.min.js";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:18:"jsTopBarButtonsTpl";a:7:{s:4:"name";s:18:"jsTopBarButtonsTpl";s:4:"desc";s:55:"TinyMCE API code for top bar buttons. Supply chunk name";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:33:"TinymceWrapperNPpublishButtonsTpl";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:7:"mainCSS";a:7:{s:4:"name";s:7:"mainCSS";s:4:"desc";s:0:"";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:2832:"<style>
.np_button_form {
display: none !important;
}

.mce-tinymce-inline {
border-width: 0 !important;
}

[data-tiny] {
border: 2px dotted #C1ABAB !important;
outline: 0 !important;
border-radius: 10px;
}

[data-tiny].mce-edit-focus {
box-shadow: 0px 2px 20px 1px #C31717;
outline: 0 !important;
border: 0 !important;
}

#twNpContainer {
display: none;
}

.errorMessagesNpTw {
position: fixed;
bottom: 0px;
margin: auto;
width: auto;
left: 0px;
right: 0px;
padding: 20px;
color: #FFF;
text-align: center;
z-index: 99;
font-weight: bold;
display: none;
background: #F00;
}

.publishButtons {
padding: 0;
display: block;
position: fixed;
top: 0px;
right: 0;
left: 0;
margin: auto;
font-weight: bold;
width: 100%;
text-align: center;
z-index: 9999;
font-size: 12px;
}

.publishButtons a {
float: right;
text-transform: uppercase;
display: inline-block;
padding: 15px 20px;
background: rgba(151, 151, 151, 0.99);
color: #fff;
margin: 3px;
box-shadow: 0px 0px 4px 1px #fff;
}

.bubbleNP {
max-width: 100%;
height: 37px;
}

.bubbleNP .mce-btn button {
padding: 4px 10px !important;
}

#tinymceWrapperBubbleNP {
transition: top 75ms ease-out, left 75ms ease-out;
animation: pop-upwards 180ms forwards linear;
position: absolute;
padding: 0;
display: block;
z-index: 9999;
-webkit-border-radius: 6px;
-moz-border-radius: 6px;
-ms-border-radius: 6px;
-o-border-radius: 6px;
border-radius: 6px;
-webkit-box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.75);
-moz-box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.75);
box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.75);
/*  background: -moz-linear-gradient(top, #2ca6f8 0%, #2b8ae2 100%);
background: -webkit-linear-gradient(top, #2ca6f8 0%, #2b8ae2 100%);
background: -o-linear-gradient(top, #2ca6f8 0%, #2b8ae2 100%);
background: -ms-linear-gradient(top, #2ca6f8 0%, #2b8ae2 100%);*/
filter: progid: DXImageTransform.Microsoft.gradient(startColorstr=\'#2ca6f8\', endColorstr=\'#2b8ae2\', GradientType=0);
/*background: linear-gradient(to bottom, #2ca6f8 0%, #2b8ae2 100%);*/
opacity: 0.95;
/*display: none;*/
margin: 0;
list-style: none;
/*padding: 0px 2px;*/
font-family: Arial, Helvetica, "Hiragino Sans GB", sans-serif;
text-rendering: optimizeLegibility;
white-space: nowrap;
}

#tinymceWrapperBubbleNP .mce-toolbar:nth-child(2) {
/*display:none;*/
}

#tinymceWrapperBubbleNP button {
padding: 4px 10px !important;
}

.mce-widget.mce-tooltip {
display: nonwe !important;
}

#tinymceWrapperBubbleNP .mce-panel {
background: none;
}

#tinymceWrapperBubbleNP * {
color: #000 !important;
}

.fixedToolBar {
position: static !important;
margin: 0 auto !important;
display: block !important;
width: 390px;
opacity: 1 !important;
box-shadow: none !important;
}

.fixedToolBar .mce-tinymce-inline{
margin-left: auto !important;
margin-right: auto !important;
}

.mce-popNPfields *{white-space: normal !important;}

</style>";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:11:"mainCSSfile";a:7:{s:4:"name";s:11:"mainCSSfile";s:4:"desc";s:90:"Use this to load own CSS file, to replace the inline mainCSS ...enter full url to the file";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:20:"modxGalleryAssetsUrl";a:7:{s:4:"name";s:20:"modxGalleryAssetsUrl";s:4:"desc";s:75:"The absolute link to the Assets Folder + traling slash ... site.com/assets/";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:16:"npCancelCreateId";a:7:{s:4:"name";s:16:"npCancelCreateId";s:4:"desc";s:51:"The target resource when Cancel Create link is hit.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:1:"1";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:16:"npCreateOuterTpl";a:7:{s:4:"name";s:16:"npCreateOuterTpl";s:4:"desc";s:47:"NewsPublisher outertpl. For creating resources.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:18:"twCREATEnpOuterTpl";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:16:"npCreateResource";a:7:{s:4:"name";s:16:"npCreateResource";s:4:"desc";s:150:"You can also create a brand new resource using this snippet. Create a resource and it will be used as the sample, specify the id of this resource here";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:7:"npDebug";a:7:{s:4:"name";s:7:"npDebug";s:4:"desc";s:44:"find out what"s happening with NewsPublisher";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:14:"npEditOuterTpl";a:7:{s:4:"name";s:14:"npEditOuterTpl";s:4:"desc";s:45:"NewsPublisher outertpl. For editing resources";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:16:"twEDITnpOuterTpl";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:15:"npErrorMessages";a:7:{s:4:"name";s:15:"npErrorMessages";s:4:"desc";s:64:"It is necessary to have the container class as errorMessagesNpTw";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:37:"<div class="errorMessagesNpTw"></div>";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:8:"npNoShow";a:7:{s:4:"name";s:8:"npNoShow";s:4:"desc";s:65:"Comma-separated list ids - of resources you wish to be uneditable";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:22:"npNoShowChildOfParents";a:7:{s:4:"name";s:22:"npNoShowChildOfParents";s:4:"desc";s:107:"Comma-separated list of parent whose children are to be exempt from displaying "edit" and "create" buttons.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:16:"publishButtonCSS";a:7:{s:4:"name";s:16:"publishButtonCSS";s:4:"desc";s:0:"";s:4:"type";s:8:"textarea";s:7:"options";a:0:{}s:5:"value";s:462:"<style>
.np_button_form {
display: none !important;
}
.publishButtons {
padding: 0;
display: block;
border-radius: 50%;
position: fixed;
top: 0px;
right: 0;
left: 0;
margin: auto;
font-weight: bold;
width: 100%;
text-align: center;
z-index: 29999;
font-size: 12px;
}
.publishButtons a {
text-transform: uppercase;
display: inline-block;
padding: 5px;
background: #C31717;
color: #fff;
border-radius: 5px;
margin: 5px;
box-shadow: 0px 0px 4px 1px #fff;
}
</style>";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:10:"tinymceCDN";a:7:{s:4:"name";s:10:"tinymceCDN";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:34:"//cdn.tinymce.com/4/tinymce.min.js";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:19:"twExistingTagsChunk";a:7:{s:4:"name";s:19:"twExistingTagsChunk";s:4:"desc";s:153:"Supply the name of the chunk that contains your site-wide tags setup. E.g use getResources and tagLister to build a list. Follow the demo chunk provided.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:14:"twExistingTags";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:8:"twTagsTV";a:7:{s:4:"name";s:8:"twTagsTV";s:4:"desc";s:239:"Supply the exact name of the TV (e.g articlestags) that contains your tags. Preferably, your tags should be comma delimetered.
Make sure you supply this same name in your TinyMagicPublisher call, e.g, &show=`pagetitle,content,articlestags`";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:18:"TinymceWrapperTags";s:7:"lexicon";N;s:4:"area";s:7:"00 main";}s:9:"activetab";a:7:{s:4:"name";s:9:"activetab";s:4:"desc";s:17:"np_activetab_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:11:"allowedtags";a:7:{s:4:"name";s:11:"allowedtags";s:4:"desc";s:19:"np_allowedtags_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:144:"<p><br><a><i><em><b><strong><pre><table><th><td><tr><img><span><div><h1><h2><h3><h4><h5><font><ul><ol><li><dl><dt><dd><object><blockquote><code>";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:8:"badwords";a:7:{s:4:"name";s:8:"badwords";s:4:"desc";s:16:"np_badwords_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:8:"cancelid";a:7:{s:4:"name";s:8:"cancelid";s:4:"desc";s:16:"np_cancelid_desc";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"1";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:8:"captions";a:7:{s:4:"name";s:8:"captions";s:4:"desc";s:16:"np_captions_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:10:"clearcache";a:7:{s:4:"name";s:10:"clearcache";s:4:"desc";s:18:"np_clearcache_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:11:"contentcols";a:7:{s:4:"name";s:11:"contentcols";s:4:"desc";s:19:"np_contentcols_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:2:"60";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:11:"contentrows";a:7:{s:4:"name";s:11:"contentrows";s:4:"desc";s:19:"np_contentrows_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:2:"10";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:7:"cssfile";a:7:{s:4:"name";s:7:"cssfile";s:4:"desc";s:15:"np_cssfile_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:6:"groups";a:7:{s:4:"name";s:6:"groups";s:4:"desc";s:14:"np_groups_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:9:"hoverhelp";a:7:{s:4:"name";s:9:"hoverhelp";s:4:"desc";s:17:"np_hoverhelp_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:14:"initdatepicker";a:7:{s:4:"name";s:14:"initdatepicker";s:4:"desc";s:22:"np_initdatepicker_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:7:"initrte";a:7:{s:4:"name";s:7:"initrte";s:4:"desc";s:15:"np_initrte_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:12:"intmaxlength";a:7:{s:4:"name";s:12:"intmaxlength";s:4:"desc";s:20:"np_intmaxlength_desc";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:8:"language";a:7:{s:4:"name";s:8:"language";s:4:"desc";s:16:"np_language_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:10:"listboxmax";a:7:{s:4:"name";s:10:"listboxmax";s:4:"desc";s:18:"np_listboxmax_desc";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"8";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:18:"multiplelistboxmax";a:7:{s:4:"name";s:18:"multiplelistboxmax";s:4:"desc";s:26:"np_multiplelistboxmax_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:2:"20";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:7:"parents";a:7:{s:4:"name";s:7:"parents";s:4:"desc";s:15:"np_parents_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:6:"postid";a:7:{s:4:"name";s:6:"postid";s:4:"desc";s:14:"np_postid_desc";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:6:"prefix";a:7:{s:4:"name";s:6:"prefix";s:4:"desc";s:14:"np_prefix_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:8:"readonly";a:7:{s:4:"name";s:8:"readonly";s:4:"desc";s:16:"np_readonly_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:8:"required";a:7:{s:4:"name";s:8:"required";s:4:"desc";s:16:"np_required_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:17:"pagetitle,content";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:9:"rtcontent";a:7:{s:4:"name";s:9:"rtcontent";s:4:"desc";s:17:"np_rtcontent_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:9:"rtsummary";a:7:{s:4:"name";s:9:"rtsummary";s:4:"desc";s:17:"np_rtsummary_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:4:"show";a:7:{s:4:"name";s:4:"show";s:4:"desc";s:12:"np_show_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:98:"pagetitle,longtitle,hidemenu,published,description,menutitle,pub_date,unpub_date,introtext,content";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:11:"stopOnBadTv";a:7:{s:4:"name";s:11:"stopOnBadTv";s:4:"desc";s:19:"np_stopOnBadTv_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:11:"summarycols";a:7:{s:4:"name";s:11:"summarycols";s:4:"desc";s:19:"np_summarycols_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:2:"60";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:11:"summaryrows";a:7:{s:4:"name";s:11:"summaryrows";s:4:"desc";s:19:"np_summaryrows_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:2:"10";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:4:"tabs";a:7:{s:4:"name";s:4:"tabs";s:4:"desc";s:12:"np_tabs_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:9:"templates";a:7:{s:4:"name";s:9:"templates";s:4:"desc";s:17:"np_templates_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:13:"textmaxlength";a:7:{s:4:"name";s:13:"textmaxlength";s:4:"desc";s:21:"np_textmaxlength_desc";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:10:"tinyheight";a:7:{s:4:"name";s:10:"tinyheight";s:4:"desc";s:18:"np_tinyheight_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:9:"tinywidth";a:7:{s:4:"name";s:9:"tinywidth";s:4:"desc";s:17:"np_tinywidth_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:7:"usetabs";a:7:{s:4:"name";s:7:"usetabs";s:4:"desc";s:15:"np_usetabs_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:12:"which_editor";a:7:{s:4:"name";s:12:"which_editor";s:4:"desc";s:20:"np_which_editor_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:38:"05 NP - NewsPublisher Control Settings";}s:15:"aliasdateformat";a:7:{s:4:"name";s:15:"aliasdateformat";s:4:"desc";s:23:"np_aliasdateformat_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:11:"aliasprefix";a:7:{s:4:"name";s:11:"aliasprefix";s:4:"desc";s:19:"np_aliasprefix_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:10:"aliastitle";a:7:{s:4:"name";s:10:"aliastitle";s:4:"desc";s:18:"np_aliastitle_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:9:"cacheable";a:7:{s:4:"name";s:9:"cacheable";s:4:"desc";s:17:"np_cacheable_desc";s:4:"type";s:4:"list";s:7:"options";a:4:{i:0;a:2:{s:5:"value";s:14:"System Default";s:4:"text";s:14:"System Default";}i:1;a:2:{s:5:"value";s:3:"Yes";s:4:"text";s:3:"Yes";}i:2;a:2:{s:5:"value";s:2:"No";s:4:"text";s:2:"No";}i:3;a:2:{s:5:"value";s:6:"Parent";s:4:"text";s:6:"Parent";}}s:5:"value";s:14:"System Default";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:8:"classkey";a:7:{s:4:"name";s:8:"classkey";s:4:"desc";s:16:"np_classkey_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:8:"hidemenu";a:7:{s:4:"name";s:8:"hidemenu";s:4:"desc";s:16:"np_hidemenu_desc";s:4:"type";s:4:"list";s:7:"options";a:4:{i:0;a:2:{s:5:"value";s:14:"System Default";s:4:"text";s:14:"System Default";}i:1;a:2:{s:5:"value";s:3:"Yes";s:4:"text";s:3:"Yes";}i:2;a:2:{s:5:"value";s:2:"No";s:4:"text";s:2:"No";}i:3;a:2:{s:5:"value";s:6:"Parent";s:4:"text";s:6:"Parent";}}s:5:"value";s:14:"System Default";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:8:"parentid";a:7:{s:4:"name";s:8:"parentid";s:4:"desc";s:16:"np_parentid_desc";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:9:"published";a:7:{s:4:"name";s:9:"published";s:4:"desc";s:17:"np_published_desc";s:4:"type";s:4:"list";s:7:"options";a:4:{i:0;a:2:{s:5:"value";s:14:"System Default";s:4:"text";s:14:"System Default";}i:1;a:2:{s:5:"value";s:3:"Yes";s:4:"text";s:3:"Yes";}i:2;a:2:{s:5:"value";s:2:"No";s:4:"text";s:2:"No";}i:3;a:2:{s:5:"value";s:6:"Parent";s:4:"text";s:6:"Parent";}}s:5:"value";s:14:"System Default";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:8:"richtext";a:7:{s:4:"name";s:8:"richtext";s:4:"desc";s:16:"np_richtext_desc";s:4:"type";s:4:"list";s:7:"options";a:4:{i:0;a:2:{s:5:"value";s:14:"System Default";s:4:"text";s:14:"System Default";}i:1;a:2:{s:5:"value";s:3:"Yes";s:4:"text";s:3:"Yes";}i:2;a:2:{s:5:"value";s:2:"No";s:4:"text";s:2:"No";}i:3;a:2:{s:5:"value";s:6:"Parent";s:4:"text";s:6:"Parent";}}s:5:"value";s:14:"System Default";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:10:"searchable";a:7:{s:4:"name";s:10:"searchable";s:4:"desc";s:18:"np_searchable_desc";s:4:"type";s:4:"list";s:7:"options";a:4:{i:0;a:2:{s:5:"value";s:14:"System Default";s:4:"text";s:14:"System Default";}i:1;a:2:{s:5:"value";s:3:"Yes";s:4:"text";s:3:"Yes";}i:2;a:2:{s:5:"value";s:2:"No";s:4:"text";s:2:"No";}i:3;a:2:{s:5:"value";s:6:"Parent";s:4:"text";s:6:"Parent";}}s:5:"value";s:14:"System Default";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:8:"template";a:7:{s:4:"name";s:8:"template";s:4:"desc";s:16:"np_template_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:31:"05 NP - Resource Field Settings";}s:7:"booltpl";a:7:{s:4:"name";s:7:"booltpl";s:4:"desc";s:15:"np_booltpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}s:7:"datetpl";a:7:{s:4:"name";s:7:"datetpl";s:4:"desc";s:15:"np_datetpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}s:8:"errortpl";a:7:{s:4:"name";s:8:"errortpl";s:4:"desc";s:16:"np_errortpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}s:13:"fielderrortpl";a:7:{s:4:"name";s:13:"fielderrortpl";s:4:"desc";s:21:"np_fielderrortpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}s:6:"inttpl";a:7:{s:4:"name";s:6:"inttpl";s:4:"desc";s:14:"np_inttpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}s:13:"listoptiontpl";a:7:{s:4:"name";s:13:"listoptiontpl";s:4:"desc";s:21:"np_listoptiontpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}s:14:"optionoutertpl";a:7:{s:4:"name";s:14:"optionoutertpl";s:4:"desc";s:22:"np_optionoutertpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}s:9:"optiontpl";a:7:{s:4:"name";s:9:"optiontpl";s:4:"desc";s:17:"np_optiontpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}s:8:"outertpl";a:7:{s:4:"name";s:8:"outertpl";s:4:"desc";s:16:"np_outertpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}s:7:"texttpl";a:7:{s:4:"name";s:7:"texttpl";s:4:"desc";s:15:"np_texttpl_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:24:"newspublisher:properties";s:4:"area";s:12:"05 NP - Tpls";}}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '/*TinymceWrapper TinyMagicPublisher Snippet transforms any textareas that NewsPublisher provides.
It can manipulate any field that is hidden and restore the values right back to DOM origin

-------------------Roadmap:
-Create more beautiful themes;
-Create more beautiful plugins;
-Look for more ways to be awesome;
---------------------------

Use freely, report freely, enjoy freely
Dedicated to those who have cried
---------------------------

http://www.leofec.com/modx-revolution/
-donshakespeare in the MODx forum
*/

//TinyMagicPublisherModifier Snippet
//empty value gets default_text for both new and existing docs
/*if (isset($_GET[\'edit\']) && $_GET[\'edit\'] == \'true\' || isset($_GET[\'create\']) && $_GET[\'create\'] == \'true\' ) {
  if($input){
    if ($modx->resource->get(\'createdby\') == $modx->user->get(\'id\') ) {
      $legalOWNER = 1;
    }
    if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') && !$modx->hasPermission(\'new_document\') ) {
      return;
    }
    $typesOfResourcesFields = array("pagetitle","alias","menutitle","longtitle","introtext","description","content");
    if(in_array($name, $typesOfResourcesFields)) {
      if($_POST[$name]){
        $name = $_POST[$name];
      }
      else{
        $name = $modx->resource->get($name);
      }
    }
    else{
      if($_POST[$name]){
        if($_POST[$name] == \'\'){
          $name = \'\';
        }
        $name = $_POST[$name];
      }
      else{
        $name = $modx->resource->getTVValue($name);
      }
    }
    $name = str_replace(array(\'[[\',\']]\'),array(\'{{\',\'}}\'),$name);
    return $name;
  }
}*/
function listArray($thisList){
  $thisList = preg_replace(\'/\\s+/\', \'\', $thisList);
  $thisList = preg_replace("/[^a-z0-9,-_]+/i", \' \', $thisList);
  $thisList = explode(\',\', $thisList);
  return $thisList;
}
function cssANDjs($mainCSSfile,$mainCSS,$jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions, $TinyJSONGalleryJS){
  global $modx;
  //register CSS and JS to browser for MAGIC mode
  if($mainCSSfile){
    $modx->regClientCSS($mainCSSfile);
  }
  else{
    $modx->regClientStartupHTMLBlock($mainCSS);
  }
  if($jQueryCDN){
    $modx->regClientScript($jQueryCDN);
  }
  $modx->regClientScript($tinymceCDN);
  $modx->regClientHTMLBlock($tinyInitsANDfunctions);
  if($TinyJSONGalleryJS){
    $modx->regClientScript($TinyJSONGalleryJS);
  }
}
function cssANDjsplain($jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions,$TinyJSONGalleryJS){
  global $modx;
  //register CSS and JS to browser for MAGIC mode
  if($jQueryCDN){
    $modx->regClientScript($jQueryCDN);
  }
  $modx->regClientScript($tinymceCDN);
  $modx->regClientHTMLBlock($tinyInitsANDfunctions);
  if($TinyJSONGalleryJS){
    $modx->regClientScript($TinyJSONGalleryJS);
  }
}
function redirectTo($desti,$param){
  global $modx;
  $url = $modx->makeUrl($desti,\'\',$param,\'full\');
  $modx->sendRedirect($url);
}
//let\'s set up some restrictions
if (isset($_GET[\'edit\']) && !isset($_GET[\'create\']) && $modx->resource->get(\'id\') !== $npCreateResource ) {
  $legalEDITparam = 1;
}
if (isset($_GET[\'create\']) && !isset($_GET[\'edit\']) && $modx->resource->get(\'id\') == $npCreateResource ) {
  $legalCREATEparam = 1;
}
if (!$modx->hasPermission(\'new_document\') || $_GET[\'create\'] !== \'true\') {
  $legalHASpermission = 1;
}
if ($modx->resource->get(\'createdby\') == $modx->user->get(\'id\') || $modx->user->isMember(listArray($editorAdminGroups))) {
  $legalOWNER = 1;
}
if($npPureMagicNonTraditional){
  //force disable a few NP calls
  $deletebutton = $duplicatebutton = 0;
  //never edit the (create) page - from which new resources will be created
  if($modx->resource->get(\'id\') == $npCreateResource && isset($_GET[\'edit\'])) {
    redirectTo($npCancelCreateId,\'\');
  }
  //never load the (create) page without the \'create\' url parameter
  if($modx->resource->get(\'id\') == $npCreateResource && !isset($_GET[\'create\']) ) {
    redirectTo($npCreateResource,\'create=true\');
  }
  //do not show \'edit\' or \'create\' buttons or load NewsPublisher on any pages specified
  if($npNoShow){
    $npNoShow = listarray($npNoShow);
    $rId = $modx->resource->get(\'id\');
    if(in_array((string)$rId, $npNoShow, true)) {
     return;
    }
  }
  //force a few NP settings
  $rtsummary = $rtcontent = $usetabs = 0;
  $which_editor = \'\';
  $richtext = \'No\';

  //do not show \'edit\' or \'create\' buttons or load Newspublisher on children with specified parentid
  if($npNoShowChildOfParents){
    $npNoShowChildOfParents = listarray($npNoShowChildOfParents);
    $parentId = $modx->resource->get(\'parent\');
    if(in_array((string)$parentId, $npNoShowChildOfParents, true)) {
     return;
    }
  }

  //superficially show or hide NewsPublisher form on client when debug status changes
  $npHide = \'style="display:none !important;"\';
  if($npDebug == 1) {
     $cssfile = \'\';
     $npHide = \'style="display:block !important;"\';
  }

  //use custom NewsPublisher outertpl to ensure <form action> url has the correct create/edit parameter
  if($modx->resource->get(\'id\') == $npCreateResource){
    $outertpl = $npCreateOuterTpl;
  }
  else{
    $outertpl = $npEditOuterTpl;
  }

  //quickly set variable to be pushed over to JS to see if to update resource with NewspUblisher content //now replaced by TMPModifier
  $npCreate = "\'\'";
  if (isset($_GET[\'edit\']) && $_GET[\'edit\'] == \'true\' && !isset($_GET[\'create\']) && $modx->resource->get(\'id\') !== $npCreateResource ) {
    $npCreate = 1;
  }

  //prepare the top bar buttons
  //no need for chunkSuffix, since calls to this are in template (@psets)
  if($jsTopBarButtonsTpl){
    if($npCreate == 1){
      $cancelHREF = $modx->resource->get(\'id\');
    }
    else{
      $cancelHREF = $npCancelCreateId;

    }
    $topBar = $modx->getChunk($jsTopBarButtonsTpl, array("cancelid" => $cancelHREF));
  }

  $errorJSandContentRefresh = \'
    var npTWerrTime;
    function npErrors(){
      clearTimeout(npTWerrTime);
      if (!$("#newspublisherForm").length) {
         $(".errorMessagesNpTw").fadeIn().html("NewsPublisher did not load: is it installed?");
      }
      if ($(".newspublisher > .errormessage").length) {
        // var err = $(".newspublisher").clone().children().remove().end().text();
        $(".errorMessagesNpTw").html("");
         $(".newspublisher > .errormessage").each(function(i, obj) {
            $(".errorMessagesNpTw").fadeIn().html($(".errorMessagesNpTw").html()+$(this).text()+"<br>");
         });
      }
      if ($(".fielderrormessage").length) {
        $(".errorMessagesNpTw").html("");
         $(".fielderrormessage").each(function(i, obj) {
            $(".errorMessagesNpTw").fadeIn().html($(".errorMessagesNpTw").html()+$(this).text()+"<br>");
         });

         // $("[data-tiny]").each(function(){
         //  npStuff = $(this).attr("data-tiny");
         //  npStuffVal = $("#"+npStuff).val();
         //  $(this).html(npStuffVal);
         //  })
      }
       npTWerrTime = setTimeout(function(){
         $(".errorMessagesNpTw").slideUp();
       },8500);
    }
    npErrors();
    npCreate = \'.$npCreate.\';
    // if(npCreate == 1){
    //    $("[data-tiny]").each(function(){
    //     npStuff = $(this).attr("data-tiny");
    //     npStuffVal = $("#"+npStuff).val();
    //     $(this).html(npStuffVal);
    //     })
    // }
    $("[data-tiny]").addClass("twNPactive")
  \';

  //get chunks according to NewsPublisher &show=`pagetitle,content,TV1`
  //this allows user to use custom JS (especially TinyMCE inits only when needed) for each textfield that will be reporting back to NewsPublisher on the client side.

  $commonTinyMCECode = $modx->getChunk(\'TinymceWrapperNPCommonCode\'.$chunkSuffix);
  $commonTinyMCECode = $commonTinyMCECode ? $commonTinyMCECode : \'\';
  $getTWchunks = "";
  $npshow = listarray($show);
  $i = 0;
  foreach ($npshow as $s) {
    $npshow[$i] = $modx->getChunk(\'TinymceWrapperNP\'.$s.$chunkSuffix, array(\'commonTinyMCECode\'=>$commonTinyMCECode));
    $getTWchunks.= $npshow[$i];
  }
}
else{
  $topBar = $errorJSandContentRefresh = \'\'; 
  if($npTraditionalTinyChunk){
    $getTWchunks = $modx->getChunk($npTraditionalTinyChunk);
  }
}


//let\'s setup some functions and file select callbacks for our supported file browsers
switch ($autoFileBrowser) {
  case \'elFinderBrowser\':
    $browserRTEurl = $elFinderBrowserRTEurl;
    $browserRTEtitle = $elFinderBrowserRTEtitle;
    break;
  case \'responsivefilemanagerBrowser\':
    $browserRTEurl = $responsivefilemanagerBrowserRTEurl;
    $browserRTEtitle = $responsivefilemanagerBrowserRTEtitle;
    break;
  case \'roxyFilemanBrowser\':
    $browserRTEurl = $roxyFilemanBrowserRTEurl;
    $browserRTEtitle = $roxyFilemanBrowserRTEtitle;
    break;
}
if ($autoFileBrowser == \'responsivefilemanagerBrowser\') {
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win, image) {
      myImage = image;
      resp = "\'.$browserRTEurl.\'";
      if (resp.indexOf("?") < 0) {
        resp += "?field_id=" + field_name;
      }
      else {
        resp += "&field_id=" + field_name;
      }
      tinyMCE.activeEditor.windowManager.open({
        // console.log(resp);
        title: "\'.$browserRTEtitle.\'",
        url: resp,
        width : window.innerWidth / 1.2,
        height : window.innerHeight /1.2
      }, {
        oninsert: function(url) {
         if(field_name.indexOf("np-") > -1){
          document.getElementById(field_name).value = url;
          tinyMCE.activeEditor.windowManager.close();
         }
         else{
          win.document.getElementById(field_name).value = url;
         }
        }
      });
    return false;
    }
    function responsive_filemanager_callback(field_id){
      tinyMCE.activeEditor.windowManager.close();
      if(myImage){
        var url=$("#"+field_id).val();
        $("[data-tiny-image="+field_id+"]").attr("src",url);
        tinyMCE.activeEditor.windowManager.alert("Image updated");
     }
    }
  \';
}
elseif ($autoFileBrowser == \'roxyFilemanBrowser\') {
  $browserFunctions=\'
    autoFileBrowser = \'.$autoFileBrowser.\';
      function \'.$autoFileBrowser.\'(field_name, url, type, win, image) {
        myImage = image;
        roxyFileman = "\'.$browserRTEurl.\'";
        if (roxyFileman.indexOf("?") < 0) {
          roxyFileman += "?type=" + type;
        }
        else {
          roxyFileman += "&type=" + type;
        }
        if(field_name.indexOf("np-") > -1){
          roxyFileman += "&input=" + field_name + "&value=" + document.getElementById(field_name).value;
        }
        else{
          roxyFileman += "&input=" + field_name + "&value=" + win.document.getElementById(field_name).value;
        }
        if(tinyMCE.activeEditor.settings.language){
          roxyFileman += "&langCode=" + tinyMCE.activeEditor.settings.language;
        }
        if(myImage){
           fieldValue = document.getElementById(field_name);
           var oldFieldValue = fieldValue.value;
           var checkChange = setInterval(function() {
            if(fieldValue.value != oldFieldValue) {
                $(fieldValue).trigger("change");
                $("[data-tiny-image="+field_name+"]").attr("src",fieldValue.value);
                tinyMCE.activeEditor.windowManager.alert("Image updated");
                clearInterval(checkChange);
            }
           },500);
        }
        tinyMCE.activeEditor.windowManager.open({
          title: "\'.$browserRTEtitle.\'",
          url: roxyFileman,
          plugins: "media",
          width : window.innerWidth / 1.2,
          height : window.innerHeight / 1.2
        },
        {
         onclose:function(){
          alert("closed")
         }
        });
      return false;
      }
  \';
}
else{
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win, image) {
      myImage = image;
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: "\'.$browserRTEurl.\'",
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2
      }, {
        oninsert: function(url) {
         if(myImage){
            document.getElementById(field_name).value = url;
            $("[data-tiny-image="+field_name+"]").attr("src",url);
            tinyMCE.activeEditor.windowManager.alert("Image updated");
         }
         else{
          if(win){
            win.document.getElementById(field_name).value = url;
          }
          else{
            document.getElementById(field_name).value = url;
          }
         }
        }
      });
    return false;
    }
  \';
}


//prepare NewsPublisher snippet call with all possible default settings
$sD = \'System Default\';

//runSnippet (which used to cause session problems)
 $newsPublisher = \'
[[!NewsPublisher? &activetab=`\'.$activetab.\'` &badwords=`\'.$badwords.\'` &allowedtags=`\'.$allowedtags.\'` &captions=`\'.$captions.\'` &clearcache=`\'.$clearcache.\'` &contentcols=`\'.$clearcache.\'` &contentrows=`\'.$clearcache.\'` &cssfile=`\'.$cssfile.\'` &groups=`\'.$groups.\'` &hoverhelp=`\'.$hoverhelp.\'` &initdatepicker=`\'.$initdatepicker.\'` &initrte=`\'.$initrte.\'` &intmaxlength=`\'.$intmaxlength.\'` &language=`\'.$language.\'` &listboxmax=`\'.$listboxmax.\'` &multiplelistboxmax=`\'.$multiplelistboxmax.\'` &parents=`\'.$parents.\'` &postid=`\'.$postid.\'` &prefix=`\'.$prefix.\'` &readonly=`\'.$readonly.\'` &required=`\'.$required.\'` &rtcontent=`\'.$rtcontent.\' `&rtsummary=`\'.$rtsummary.\'` &show=`\'.$show.\'` &stopOnBadTv=`\'.$stopOnBadTv.\'` &summarycols=`\'.$summarycols.\'` &summaryrows=`\'.$summaryrows.\'` &tabs=`\'.$tabs.\'` &templates=`\'.$templates.\'` &textmaxlength=`\'.$textmaxlength.\'` &tinyheight=`\'.$tinyheight.\'` &tinywidth=`\'.$tinywidth.\'` &usetabs=`\'.$usetabs.\'` &which_editor=`\'.$which_editor.\'` &aliasdateformat=`\'.$aliasdateformat.\'` &aliasprefix=`\'.$aliasprefix.\'` &aliastitle=`\'.$aliastitle.\'` &cacheable=`\'.$cacheable.\'` &classkey=`\'.$classkey.\'` &hidemenu=`\'.$hidemenu.\'` &parentid=`\'.$parentid.\'` &published=`\'.$published.\'` &richtext=`\'.$richtext.\'` &searchable=`\'.$searchable.\'` &template=`\'.$template.\'` &templateid=`\'.$templateid.\'` &booltpl=`\'.$booltpl.\'` &datetpl=`\'.$datetpl.\'` &errortpl=`\'.$errortpl.\'` &fielderrortpl=`\'.$fielderrortpl.\'` &inttpl=`\'.$inttpl.\'` &listoptiontpl=`\'.$listoptiontpl.\'` &listoutertpl=`\'.$listoutertpl.\'` &optionoutertpl=`\'.$optionoutertpl.\'` &optiontpl=`\'.$optiontpl.\'` &outertpl=`\'.$outertpl.\'` &texttpl=`\'.$texttpl.\'` &textareatpl=`\'.$textareatpl.\'` &cancelid=`\'.$cancelid.\'` &activetabs=`\'.$activetabs.\'` &shownotify=`\'.$shownotify.\'` &notifyChecked=`\'.$notifyChecked.\'` &editHome=`\'.$editHome.\'` &imagetpl=`\'.$imagetpl.\'` &filetpl=`\'.$filetpl.\'` &duplicatebutton=`\'.($duplicatebutton ?: 0).\'` &deletebutton=`\'.($deletebutton ?: 0).\'` &confirmdelete=`\'.($confirmdelete ?: 0).\'` &published_default=`\'.($published_default ? $published_default : $sD).\'` &hidemenu_default=`\'.($hidemenu_default ? $hidemenu_default : $sD).\'` &cacheable_default=`\'.($cacheable_default ? $cacheable_default : $sD).\'` &searchable_default=`\'.($searchable_default ? $searchable_default : $sD).\'` &richtext_default=`\'.($richtext_default ? $richtext_default : $sD).\'`]]
 \';
// $newsPublisher = $modx->runSnippet(\'NewsPublisher\',
//   array(
//   \'activetab\'=>$activetab,
//   \'badwords\'=>$badwords,
//   \'allowedtags\'=>$allowedtags,
//   \'captions\'=>$captions,
//   \'clearcache\'=>$clearcache,
//   \'contentcols\'=>$clearcache,
//   \'contentrows\'=>$clearcache,
//   \'cssfile\'=>$cssfile,
//   \'groups\'=>$groups,
//   \'hoverhelp\'=>$hoverhelp,
//   \'initdatepicker\'=>$initdatepicker,
//   \'initrte\'=>$initrte,
//   \'intmaxlength\'=>$intmaxlength,
//   \'language\'=>$language,
//   \'listboxmax\'=>$listboxmax,
//   \'multiplelistboxmax\'=>$multiplelistboxmax,
//   \'parents\'=>$parents,
//   \'postid\'=>$postid,
//   \'prefix\'=>$prefix,
//   \'readonly\'=>$readonly,
//   \'required\'=>$required,
//   \'rtcontent\'=>$rtcontent,
//   \'rtsummary\'=>$rtsummary,
//   \'show\'=>$show,
//   \'stopOnBadTv\'=>$stopOnBadTv,
//   \'summarycols\'=>$summarycols,
//   \'summaryrows\'=>$summaryrows,
//   \'tabs\'=>$tabs,
//   \'templates\'=>$templates,
//   \'textmaxlength\'=>$textmaxlength,
//   \'tinyheight\'=>$tinyheight,
//   \'tinywidth\'=>$tinywidth,
//   \'usetabs\'=>$usetabs,
//   \'which_editor\'=>$which_editor,
//   \'aliasdateformat\'=>$aliasdateformat,
//   \'aliasprefix\'=>$aliasprefix,
//   \'aliastitle\'=>$aliastitle,
//   \'cacheable\'=>$cacheable,
//   \'classkey\'=>$classkey,
//   \'hidemenu\'=>$hidemenu,
//   \'parentid\'=>$parentid,
//   \'published\'=>$published,
//   \'richtext\'=>$richtext,
//   \'searchable\'=>$searchable,
//   \'template\'=>$template,
//   \'templateid\'=>$templateid,
//   \'booltpl\'=>$booltpl,
//   \'datetpl\'=>$datetpl,
//   \'errortpl\'=>$errortpl,
//   \'fielderrortpl\'=>$fielderrortpl,
//   \'inttpl\'=>$inttpl,
//   \'listoutertpl\'=>$listouterpl,
//   \'listoptiontpl\'=>$listoptiontpl,
//   \'optionoutertpl\'=>$optionoutertpl,
//   \'optiontpl\'=>$optiontpl,
//   \'outertpl\'=>$outertpl,
//   \'texttpl\'=>$texttpl,
//   \'textareatpl\'=>$textareatpl,
//   \'cancelid\'=>$cancelid,
//   \'activetabs\'=>$activetabs,
//   \'shownotify\'=>$shownotify,
//   \'notifyChecked\'=>$notifyChecked,
//   \'editHome\'=>$editHome,
//   \'imagetpl\'=>$imagetpl,
//   \'filetpl\'=>$filetpl,
//   \'duplicatebutton\'=>($duplicatebutton ?: 0),
//   \'deletebutton\'=>($deletebutton ?: 0),
//   \'confirmdelete\'=>($confirmdelete ?: 0),
//   \'published_default\'=>($published_default ? $published_default : $sD),
//   \'hidemenu_default\'=>($hidemenu_default ? $hidemenu_default : $sD),
//   \'cacheable_default\'=>($cacheable_default ? $cacheable_default : $sD),
//   \'searchable_default\'=>($searchable_default ? $searchable_default : $sD),
//   \'richtext_default\'=>($richtext_default ? $richtext_default : $sD)
//   )
// ) ? : \'\';

//grab tag TV name
$twTagsTV = $twTagsTV ? : \'doesNotExist\';
//get possible getResource call that builds the <select> Tags site-wide
$twExistingTagsChunk = $modx->getChunk($twExistingTagsChunk);

//prepare TinyMCE for the frontend
$tinyInitsANDfunctions = \'
  <script type="text/javascript">
    twTAGtvID = "\'.$twTagsTV.\'";
    modxGalleryAssetsUrl = "\'.$modxGalleryAssetsUrl.\'";
    galleryBackUpRTEskin  = "\'.$galleryBackUpRTEskin .\'";
    \'.$errorJSandContentRefresh.\'
    \'.$browserFunctions.\'
    \'.$topBar.\'
    $(document).ready(function() {
      \'.$getTWchunks.\'
      });
  </script>
 \';

//prepare buttons and final NP call to be echoed
$buttonsANDnewspublisher  = \'<div id="tinymceWrapperBubbleNP"></div><div class="publishButtons"></div>\'.$npErrorMessages.\'<div id="twNpContainer" \'.$npHide.\'>\'.$twExistingTagsChunk.$newsPublisher.\'</div>\';

//do the plain ol\' NP
if($npPureMagicNonTraditional == 0){
  cssANDjsplain($jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions,$TinyJSONGalleryJS);
  echo $newsPublisher;
}
//supercharge the plain ol\' NP
else{
  //reset these session variables so that NewsPublisher can have clean slate for Editing existing resource or creating a new one
  if(isset($_SESSION[\'np_doc_id\'], $_SESSION[\'np_doc_to_edit\'])) {
    unset($_SESSION[\'np_doc_id\'], $_SESSION[\'np_doc_to_edit\']);
  }

  //grab url parameters and init NewsPublisher accordingly
  if ($legalEDITparam == 1) {
    //return to resource url withou the edit parameter
    if ($_GET[\'edit\'] !== \'true\' || !$legalOWNER == 1) {
      redirectTo($modx->resource->get(\'id\'),\'\');
    }
    //restrict editing to owner of resource or Admin editors
    if ($legalOWNER == 1) {
      //give NewsPublisher snippet some food
      $_SESSION[\'np_doc_to_edit\'] = $modx->resource->get(\'id\');
      $_SESSION[\'np_doc_id\'] = $modx->resource->get(\'id\');

      //register CSS and JS to browser
      cssANDjs($mainCSSfile,$mainCSS,$jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions,$TinyJSONGalleryJS);
      echo $buttonsANDnewspublisher;
    }
  }
  elseif ($legalCREATEparam == 1) {
    if ($legalHASpermission) {
      redirectTo($npCancelCreateId,\'\');
    }

    //register CSS and JS to browser
    cssANDjs($mainCSSfile,$mainCSS,$jQueryCDN,$tinymceCDN,$tinyInitsANDfunctions,$TinyJSONGalleryJS);
    echo $buttonsANDnewspublisher;
  }
  else{
    if($createNewButton){
      $createNewButton = \'<a href="[[~\'.$npCreateResource.\' ? &create=`true` &scheme=`full`]]">\'.$createNewButton.\'</a>\';
    }
    else{
      $createNewButton = \'\';
    }
    if($editThisButton){
      $editThisButton = \'<a href="[[~[[*id]]? &edit=`true` &scheme=`full`]]">\'.$editThisButton.\'</a>\';
    }
    else{
      $editThisButton = \'\';
    }
    if($editInManagerButton){
      $editInManagerButton = \'<a href="\'.MODX_MANAGER_URL.\'?a=resource/update&id=\'.$modx->resource->get("id").\'">\'.$editInManagerButton.\'</a>\';
    }
    else{
      $editInManagerButton = \'\';
    }
    if (!$modx->hasPermission(\'new_document\')) {
      $createNewButton = \'\';
    }
    if (!$modx->user->hasSessionContext(\'mgr\') && !$modx->hasPermission(\'edit_document\') && !$legalOWNER) {
      $editInManagerButton = \'\';
    }
    if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') ) {
      $editThisButton = \'\';
    }
    // if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') && !$modx->hasPermission(\'new_document\') ) {
    if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') && !$modx->hasPermission(\'new_document\') ) {
      return;
    }
    $editCreateButtons = \'<div class="publishButtons">\'.$editThisButton.$createNewButton.$editInManagerButton.\'</div>\';
    echo $editCreateButtons;
    $modx->regClientStartupHTMLBlock($publishButtonCSS);
  }
}',
    ),
  ),
  '78f78ef63ea42122ae33d3578d7ff26a' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinyMagicPublisherModifier ',
    ),
    'object' => 
    array (
      'id' => 8,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinyMagicPublisherModifier ',
      'description' => 'Get/Preserve exact content (esp. during error postback) that include MODX tags HTML entities.[[*pagetitle:TinyMagicPublisherModifier]]
"pagetitle","alias","menutitle","longtitle","introtext","description","content" ... anytextareas',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'snippet' => '//TinyMagicPublisherModifier Snippet
//empty value gets default_text for both new and existing docs
if (isset($_GET[\'edit\']) && $_GET[\'edit\'] == \'true\' || isset($_GET[\'create\']) && $_GET[\'create\'] == \'true\' ) {
  if($input){
    if ($modx->resource->get(\'createdby\') == $modx->user->get(\'id\') ) {
      $legalOWNER = 1;
    }
    if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') && !$modx->hasPermission(\'new_document\') ) {
      return;
    }
    $typesOfResourcesFields = array("pagetitle","alias","menutitle","longtitle","introtext","description","content");
    if(in_array($name, $typesOfResourcesFields)) {
      if($_POST[$name]){
        $name = $_POST[$name];
      }
      else{
        $name = $modx->resource->get($name);
      }
    }
    else{
      if($_POST[$name]){
        if($_POST[$name] == \'\'){
          $name = \'\';
        }
        $name = $_POST[$name];
      }
      else{
        $name = $modx->resource->getTVValue($name);
      }
    }
    $name = str_replace(array(\'[[\',\']]\'),array(\'{{\',\'}}\'),$name);
    return $name;
  }
}',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '//TinyMagicPublisherModifier Snippet
//empty value gets default_text for both new and existing docs
if (isset($_GET[\'edit\']) && $_GET[\'edit\'] == \'true\' || isset($_GET[\'create\']) && $_GET[\'create\'] == \'true\' ) {
  if($input){
    if ($modx->resource->get(\'createdby\') == $modx->user->get(\'id\') ) {
      $legalOWNER = 1;
    }
    if (!$legalOWNER || !$modx->hasPermission(\'edit_document\') && !$modx->hasPermission(\'new_document\') ) {
      return;
    }
    $typesOfResourcesFields = array("pagetitle","alias","menutitle","longtitle","introtext","description","content");
    if(in_array($name, $typesOfResourcesFields)) {
      if($_POST[$name]){
        $name = $_POST[$name];
      }
      else{
        $name = $modx->resource->get($name);
      }
    }
    else{
      if($_POST[$name]){
        if($_POST[$name] == \'\'){
          $name = \'\';
        }
        $name = $_POST[$name];
      }
      else{
        $name = $modx->resource->getTVValue($name);
      }
    }
    $name = str_replace(array(\'[[\',\']]\'),array(\'{{\',\'}}\'),$name);
    return $name;
  }
}',
    ),
  ),
  '1625b3de211ac06505743bc1cf64836d' => 
  array (
    'criteria' => 
    array (
      'category' => 'TinyMCE',
    ),
    'object' => 
    array (
      'id' => 8,
      'parent' => 1,
      'category' => 'TinyMCE',
      'rank' => 0,
    ),
  ),
  '43ae2b4e5093b1c1ffcd6a8bbf0fa0c1' => 
  array (
    'criteria' => 
    array (
      'category' => 'Backend',
    ),
    'object' => 
    array (
      'id' => 9,
      'parent' => 8,
      'category' => 'Backend',
      'rank' => 0,
    ),
  ),
  '0da3ea5d85539657a2486f4172e4709b' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperTVs',
    ),
    'object' => 
    array (
      'id' => 21,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperTVs',
      'description' => 'TinyMCE for RichTextarea TVs attached to resource via template. File and Image TVs do not need a chunk.',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  mode: "exact", //DO NOT TOUCH
  selector: ".modx-richtext", //DO NOT TOUCH
  [[+commonTinyMCECode]]
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,link,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  //valid_elements: "iframe[*],object[*],audio[*],-span[!title|!class<test test2],a[href|target|class|rel|title|data-ajax|data-iframe],strong,b,-p[class<text-align-left?text-align-center?text-align-right],br,-h1[class|data-ajax|data-iframe],-h2[class|data-ajax|data-iframe],-h3[class|data-ajax|data-iframe],-img[!src|!alt|!class=round_img|data-ajax|data-iframe],em,-blockquote,pre[class],-ol,-ul,-li,-code[class]",
  //valid_children: "-li[ul],-li[ol],-li[div],-strong[*],-em[*],-h1[*],-h2[*],-h3[*],-a[strong|em|h1|h2|h3|p|div],blockquote[p|ol|ul],pre[code],div[pre]",
  resize: true,
  autoresize_min_height: 100,
  autoresize_max_height: 400,
  toolbar: "preview fullscreen | code | newdocument | undo redo | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect | charmap emoticons insertdatetime | searchreplace",
  contextmenu: "code | removeformat | link | image",
  setup: function(editor) {
    editor.on("init", function() {
      containerId = $(editor.getContainer()).attr("id"); //float fullscreen link
      $("#" + containerId + " .mce-i-fullscreen").parent().parent().parent().parent().css("float", "right");
    });
    editor.on(\'keydown\', function(evt) { //ctrl + s = save 
      if (evt.keyCode == 83 && evt.ctrlKey && !evt.shiftKey && !evt.altKey && !evt.metaKey) {
        evt.preventDefault();
        $(\'#modx-abtn-save button\').trigger("click");
      }
      if (evt.keyCode == 27) { //escape key = remove fullscreen 
        evt.preventDefault();
        if ($(\'.mce-fullscreen\').length) {
          tinymce.activeEditor.execCommand("mceFullscreen")
        }
      }
    });
  }
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  mode: "exact", //DO NOT TOUCH
  selector: ".modx-richtext", //DO NOT TOUCH
  [[+commonTinyMCECode]]
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,link,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  //valid_elements: "iframe[*],object[*],audio[*],-span[!title|!class<test test2],a[href|target|class|rel|title|data-ajax|data-iframe],strong,b,-p[class<text-align-left?text-align-center?text-align-right],br,-h1[class|data-ajax|data-iframe],-h2[class|data-ajax|data-iframe],-h3[class|data-ajax|data-iframe],-img[!src|!alt|!class=round_img|data-ajax|data-iframe],em,-blockquote,pre[class],-ol,-ul,-li,-code[class]",
  //valid_children: "-li[ul],-li[ol],-li[div],-strong[*],-em[*],-h1[*],-h2[*],-h3[*],-a[strong|em|h1|h2|h3|p|div],blockquote[p|ol|ul],pre[code],div[pre]",
  resize: true,
  autoresize_min_height: 100,
  autoresize_max_height: 400,
  toolbar: "preview fullscreen | code | newdocument | undo redo | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect | charmap emoticons insertdatetime | searchreplace",
  contextmenu: "code | removeformat | link | image",
  setup: function(editor) {
    editor.on("init", function() {
      containerId = $(editor.getContainer()).attr("id"); //float fullscreen link
      $("#" + containerId + " .mce-i-fullscreen").parent().parent().parent().parent().css("float", "right");
    });
    editor.on(\'keydown\', function(evt) { //ctrl + s = save 
      if (evt.keyCode == 83 && evt.ctrlKey && !evt.shiftKey && !evt.altKey && !evt.metaKey) {
        evt.preventDefault();
        $(\'#modx-abtn-save button\').trigger("click");
      }
      if (evt.keyCode == 27) { //escape key = remove fullscreen 
        evt.preventDefault();
        if ($(\'.mce-fullscreen\').length) {
          tinymce.activeEditor.execCommand("mceFullscreen")
        }
      }
    });
  }
});',
    ),
  ),
  '011f7750f5a93f82fbd39abf30fe70ee' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperContent',
    ),
    'object' => 
    array (
      'id' => 22,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperContent',
      'description' => 'TinyMCE for Resource Content Textarea',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  selector: "#ta",
  [[+commonTinyMCECode]]
  statusbar: true,
  plugins: "imagetools,autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  //paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  //valid_elements: "iframe[*],object[*],audio[*],-span,a[href|target|class|rel|title|onclick],-strong,-b,-p[class<text-align-left?text-align-center?text-align-right],br,-h1,-h2,-h3,-figure,-figcaption,-img[!src|!alt],em,-blockquote,pre[class],-ol,-ul,-li,-code,hr[*]",
  //valid_children: "figure[img|figcaption],-li[ul],-li[ol],-li[div],-strong[*],-em[*],-h1[*],-h2[*],-h3[*],-a[strong|em|h1|h2|h3|p|div],blockquote[p|ol|ul],pre[code],div[pre]",
  resize: true,
  autoresize_min_height: 100,
  toolbar: "preview fullscreen | twPreCodeManager code | undo redo | newdocument |  blockquote | bold italic aligncenter | bullist numlist | link unlink | image | styleselect charmap emoticons insertdatetime searchreplace bubbleBarOptionsButton",
  contextmenu: "code | twPreCodeManager | fullscreen | removeformat | link | image"
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  selector: "#ta",
  [[+commonTinyMCECode]]
  statusbar: true,
  plugins: "imagetools,autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  //paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  //valid_elements: "iframe[*],object[*],audio[*],-span,a[href|target|class|rel|title|onclick],-strong,-b,-p[class<text-align-left?text-align-center?text-align-right],br,-h1,-h2,-h3,-figure,-figcaption,-img[!src|!alt],em,-blockquote,pre[class],-ol,-ul,-li,-code,hr[*]",
  //valid_children: "figure[img|figcaption],-li[ul],-li[ol],-li[div],-strong[*],-em[*],-h1[*],-h2[*],-h3[*],-a[strong|em|h1|h2|h3|p|div],blockquote[p|ol|ul],pre[code],div[pre]",
  resize: true,
  autoresize_min_height: 100,
  toolbar: "preview fullscreen | twPreCodeManager code | undo redo | newdocument |  blockquote | bold italic aligncenter | bullist numlist | link unlink | image | styleselect charmap emoticons insertdatetime searchreplace bubbleBarOptionsButton",
  contextmenu: "code | twPreCodeManager | fullscreen | removeformat | link | image"
});',
    ),
  ),
  '22b417438d53b534a2322f48a39bb5c4' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperIntrotext',
    ),
    'object' => 
    array (
      'id' => 23,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperIntrotext',
      'description' => 'TinyMCE for Resource Introtext Textarea',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  selector: "#modx-resource-introtext",
  [[+commonTinyMCECode]]
  plugins: "contextmenu,charmap,paste,link,preview,code,insertdatetime",
  toolbar: "code | undo redo | newdocument | preview | styleselect | blockquote | bold | italic | bullist numlist | link unlink | charmap | insertdatetime",
  contextmenu: "link,code,styleselect",
  resize: true,
  forced_root_block: \'\',
  force_br_newlines: true,
  force_p_newlines: false,
  //valid_elements: "div,h1,h2,h3,h4,h5,h6,strong,b,i,em,span[*],blockquote,ol,ul,li,a",
 //paste_word_valid_elements: "div,h1,h2,h3,h4,h5,h6,strong,b,i,em,span,blockquote,ol,ul,li,a",
  elementpath: false
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  selector: "#modx-resource-introtext",
  [[+commonTinyMCECode]]
  plugins: "contextmenu,charmap,paste,link,preview,code,insertdatetime",
  toolbar: "code | undo redo | newdocument | preview | styleselect | blockquote | bold | italic | bullist numlist | link unlink | charmap | insertdatetime",
  contextmenu: "link,code,styleselect",
  resize: true,
  forced_root_block: \'\',
  force_br_newlines: true,
  force_p_newlines: false,
  //valid_elements: "div,h1,h2,h3,h4,h5,h6,strong,b,i,em,span[*],blockquote,ol,ul,li,a",
 //paste_word_valid_elements: "div,h1,h2,h3,h4,h5,h6,strong,b,i,em,span,blockquote,ol,ul,li,a",
  elementpath: false
});',
    ),
  ),
  'ef93325b7a1ef8304e7e124aad9bbe5f' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperDescription',
    ),
    'object' => 
    array (
      'id' => 24,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperDescription',
      'description' => 'TinyMCE for Resource Description Textarea',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  selector: "#modx-resource-description",
  [[+commonTinyMCECode]]
  plugins: "contextmenu,charmap,paste,link,preview,code,insertdatetime",
  toolbar: "code | undo redo | newdocument | preview | styleselect | blockquote | bold | italic | bullist numlist | link unlink | charmap | insertdatetime",
  contextmenu: "link,code,styleselect",
  resize: true,
  forced_root_block: \'\',
  force_br_newlines: true,
  force_p_newlines: false,
  //valid_elements: "div,h1,h2,h3,h4,h5,h6,strong,b,i,em,span[*],blockquote,ol,ul,li,a,br",
  //paste_word_valid_elements: "div,h1,h2,h3,h4,h5,h6,strong,b,i,em,span,blockquote,ol,ul,li,a,br",
  elementpath: false
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  selector: "#modx-resource-description",
  [[+commonTinyMCECode]]
  plugins: "contextmenu,charmap,paste,link,preview,code,insertdatetime",
  toolbar: "code | undo redo | newdocument | preview | styleselect | blockquote | bold | italic | bullist numlist | link unlink | charmap | insertdatetime",
  contextmenu: "link,code,styleselect",
  resize: true,
  forced_root_block: \'\',
  force_br_newlines: true,
  force_p_newlines: false,
  //valid_elements: "div,h1,h2,h3,h4,h5,h6,strong,b,i,em,span[*],blockquote,ol,ul,li,a,br",
  //paste_word_valid_elements: "div,h1,h2,h3,h4,h5,h6,strong,b,i,em,span,blockquote,ol,ul,li,a,br",
  elementpath: false
});',
    ),
  ),
  '9de3ce7c3b21ad3c6664f38929fe791a' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperLinkList',
    ),
    'object' => 
    array (
      'id' => 25,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperLinkList',
      'description' => 'So you want to select a MODx resource from within TInyMCE, but don\'t want to write your own PHP snippet huh? Okay here is a cheat....watch out for JSON trailing commas. To use this with modxMagicHoverlink plugin change value to value: \'\\[\\[~[[+id]]\\]\\]\' .',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => '[[-remove the pdoMenu dash prefix if you have pdoMenu installed]]
[[-pdoMenu?
  &context=`web`
  &parents=`39,5,9`
  &level=`100`
  &limit=`1000`
  &showHidden =`1`
  &showUnpublished =`1`
  &tplOuter=`@INLINE [[+wrapper]]`
  &tpl=`@INLINE {title: "[[+menutitle]]", value: "\\[\\[~[[+id]]\\]\\]"},`
  &tplParentRow=`@INLINE {title: "[[+menutitle]]", value: "\\[\\[~[[+id]]\\]\\]", menu:[ [[+wrapper]] ]},`
  &sortby=`menuindex`
  &sortdir=`ASC`
  &checkPermissions=`load,list,view`
]]',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '[[-remove the pdoMenu dash prefix if you have pdoMenu installed]]
[[-pdoMenu?
  &context=`web`
  &parents=`39,5,9`
  &level=`100`
  &limit=`1000`
  &showHidden =`1`
  &showUnpublished =`1`
  &tplOuter=`@INLINE [[+wrapper]]`
  &tpl=`@INLINE {title: "[[+menutitle]]", value: "\\[\\[~[[+id]]\\]\\]"},`
  &tplParentRow=`@INLINE {title: "[[+menutitle]]", value: "\\[\\[~[[+id]]\\]\\]", menu:[ [[+wrapper]] ]},`
  &sortby=`menuindex`
  &sortdir=`ASC`
  &checkPermissions=`load,list,view`
]]',
    ),
  ),
  'bf68c7757473e2c175b975476b071a41' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperQuickUpdate',
    ),
    'object' => 
    array (
      'id' => 26,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperQuickUpdate',
      'description' => 'TinyMCE for Quick Create/Update resources from Resource tree',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  selector: quickyId, //DO NOT TOUCH
  [[+commonTinyMCECode]]
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,link,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  //paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  //valid_elements: "iframe[*],object[*],audio[*],-span[!title|!class<test test2],a[href|target|class|rel|title|data-ajax|data-iframe],strong,b,-p[class<text-align-left?text-align-center?text-align-right],br,-h1[class|data-ajax|data-iframe],-h2[class|data-ajax|data-iframe],-h3[class|data-ajax|data-iframe],-img[!src|!alt|!class=round_img|data-ajax|data-iframe],em,-blockquote,pre[class],-ol,-ul,-li,-code[class]",
  //valid_children: "-li[ul],-li[ol],-li[div],-strong[*],-em[*],-h1[*],-h2[*],-h3[*],-a[strong|em|h1|h2|h3|p|div],blockquote[p|ol|ul],pre[code],div[pre]",
  resize: true,
  autoresize_min_height: 100,
  toolbar: "preview fullscreen | code | undo redo | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect",
  contextmenu: "fullscreen removeformat | link | image | code charmap searchreplace",
  setup: function(editor) {
    editor.on(\'keydown\', function(evt) { //ctrl + s = Save 
      if (evt.keyCode == 83 && evt.ctrlKey && !evt.shiftKey && !evt.altKey && !evt.metaKey) {
        evt.preventDefault();
        $(\'#\' + editor.id).parents(\'.modx-window\').find(\'button:contains("Save")\').first().trigger("click");
      }
      if (evt.keyCode == 27) { //escape key = close or remove fullscreen
        evt.preventDefault();
        if ($(\'.mce-fullscreen\').length) {
          tinymce.activeEditor.execCommand("mceFullscreen")
        } else {
          $(\'#\' + editor.id).parents(\'.modx-window\').find(\'button:contains("Close")\').first().trigger("click");
        }
      }
    });
    editor.on(\'mouseleave\', function(evt) { //sync textarea with TinyMCE 
      tinyMCE.activeEditor.save();
    });
    editor.on("init", function() { //float fullscreen link
      containerId = $(editor.getContainer()).attr("id");
      $("#" + containerId + " .mce-i-fullscreen").parent().parent().parent().parent().css("float", "right");
    });
  }
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  selector: quickyId, //DO NOT TOUCH
  [[+commonTinyMCECode]]
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,link,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  //paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  //valid_elements: "iframe[*],object[*],audio[*],-span[!title|!class<test test2],a[href|target|class|rel|title|data-ajax|data-iframe],strong,b,-p[class<text-align-left?text-align-center?text-align-right],br,-h1[class|data-ajax|data-iframe],-h2[class|data-ajax|data-iframe],-h3[class|data-ajax|data-iframe],-img[!src|!alt|!class=round_img|data-ajax|data-iframe],em,-blockquote,pre[class],-ol,-ul,-li,-code[class]",
  //valid_children: "-li[ul],-li[ol],-li[div],-strong[*],-em[*],-h1[*],-h2[*],-h3[*],-a[strong|em|h1|h2|h3|p|div],blockquote[p|ol|ul],pre[code],div[pre]",
  resize: true,
  autoresize_min_height: 100,
  toolbar: "preview fullscreen | code | undo redo | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect",
  contextmenu: "fullscreen removeformat | link | image | code charmap searchreplace",
  setup: function(editor) {
    editor.on(\'keydown\', function(evt) { //ctrl + s = Save 
      if (evt.keyCode == 83 && evt.ctrlKey && !evt.shiftKey && !evt.altKey && !evt.metaKey) {
        evt.preventDefault();
        $(\'#\' + editor.id).parents(\'.modx-window\').find(\'button:contains("Save")\').first().trigger("click");
      }
      if (evt.keyCode == 27) { //escape key = close or remove fullscreen
        evt.preventDefault();
        if ($(\'.mce-fullscreen\').length) {
          tinymce.activeEditor.execCommand("mceFullscreen")
        } else {
          $(\'#\' + editor.id).parents(\'.modx-window\').find(\'button:contains("Close")\').first().trigger("click");
        }
      }
    });
    editor.on(\'mouseleave\', function(evt) { //sync textarea with TinyMCE 
      tinyMCE.activeEditor.save();
    });
    editor.on("init", function() { //float fullscreen link
      containerId = $(editor.getContainer()).attr("id");
      $("#" + containerId + " .mce-i-fullscreen").parent().parent().parent().parent().css("float", "right");
    });
  }
});',
    ),
  ),
  '506ae6bbae0bd7c0c69d7290181a562e' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperCommonCode',
    ),
    'object' => 
    array (
      'id' => 27,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperCommonCode',
      'description' => 'To save you some brainwidth put TinyMCE code here that repeats in your chunks. Trust me, it is easy to go crazy in this world.',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => 'skin_url: MODx.config.assets_url+\'components/tinymcewrapper/tinymceskins/modxPericles\',
link_list:[
[[$TinymceWrapperLinkList]]
],
relative_urls: false,
remove_script_host: false,
document_base_url: MODx.config.site_url,
link_class_list: [
  {title: \'None\', value: \'\'},
  {title: \'prettyLink\', value: \'prettyLink\'},
  {title: \'colorBox\', value: \'colorBox\'}
],
image_class_list: [
  {title: \'None\', value: \'\'},
  {title: \'prettyPhoto\', value: \'prettyPhoto\'},
  {title: \'colorBox\', value: \'colorBox\'}
],
image_caption: true,
browser_spellcheck: true,
gecko_spellcheck: true,
paste_data_images: false,
menubar: false,
statusbar: false,
image_advtab: true,
setup: function(editor) {
  editor.on(\'keydown\', function(evt) { 
    if (evt.keyCode == 83 && evt.ctrlKey && !evt.shiftKey && !evt.altKey && !evt.metaKey) {//ctrl + s = save 
      evt.preventDefault();
      $(\'#modx-abtn-save button\').trigger("click");
    }
    if (evt.keyCode == 27) { //escape key = remove fullscreen 
      evt.preventDefault();
      if ($(\'.mce-fullscreen\').length) {
        tinymce.activeEditor.execCommand("mceFullscreen")
      }
    }
  });
  editor.on("init",function() { //float fullscreen link
  containerId = $(editor.getContainer()).attr("id");
   $("#"+containerId+" .mce-i-fullscreen").parent().parent().parent().parent().css("float","right");
  });
},
external_plugins:{
  twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
  // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
  // bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
  // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
  modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
  twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
},
file_browser_callback : autoFileBrowser,
//leave trailing comma',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'skin_url: MODx.config.assets_url+\'components/tinymcewrapper/tinymceskins/modxPericles\',
link_list:[
[[$TinymceWrapperLinkList]]
],
relative_urls: false,
remove_script_host: false,
document_base_url: MODx.config.site_url,
link_class_list: [
  {title: \'None\', value: \'\'},
  {title: \'prettyLink\', value: \'prettyLink\'},
  {title: \'colorBox\', value: \'colorBox\'}
],
image_class_list: [
  {title: \'None\', value: \'\'},
  {title: \'prettyPhoto\', value: \'prettyPhoto\'},
  {title: \'colorBox\', value: \'colorBox\'}
],
image_caption: true,
browser_spellcheck: true,
gecko_spellcheck: true,
paste_data_images: false,
menubar: false,
statusbar: false,
image_advtab: true,
setup: function(editor) {
  editor.on(\'keydown\', function(evt) { 
    if (evt.keyCode == 83 && evt.ctrlKey && !evt.shiftKey && !evt.altKey && !evt.metaKey) {//ctrl + s = save 
      evt.preventDefault();
      $(\'#modx-abtn-save button\').trigger("click");
    }
    if (evt.keyCode == 27) { //escape key = remove fullscreen 
      evt.preventDefault();
      if ($(\'.mce-fullscreen\').length) {
        tinymce.activeEditor.execCommand("mceFullscreen")
      }
    }
  });
  editor.on("init",function() { //float fullscreen link
  containerId = $(editor.getContainer()).attr("id");
   $("#"+containerId+" .mce-i-fullscreen").parent().parent().parent().parent().css("float","right");
  });
},
external_plugins:{
  twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
  // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
  // bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
  // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
  modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
  twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
},
file_browser_callback : autoFileBrowser,
//leave trailing comma',
    ),
  ),
  '3759f8ec1142fffb9c2b0931825b877e' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperCodeMirror',
    ),
    'object' => 
    array (
      'id' => 28,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperCodeMirror',
      'description' => 'A simple chunk to teach you how to totally dominate the usage of CodeMirror according to all the possible specs provided on the official website',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => '/*
#######################################################
TinymceWrapperCodeMirror is a chunk to let any whoever dominate the initialization of the powerful CodeMirror code editor.
This tiny config can work any where and with anything in MODX.
It will sync with TinyMCE if TinyMCE RTE is detected

Yours truly, donshakespeare @ MODX forums
#######################################################

##Language / Mode / Syntax
"mode": "html",
"mode": "auto/html",
  -- (elements) static source extension dependent - fallback to html
  -- (resources) content type (mimetype) dependent - fallsback to html

##max-height of editor in pixels
"maxHeight": pure integer / "textarea" / "Infinity",
  -- integer - e.g 100 (will make height 100px)
  -- "textarea" - editor will borrow the fixed height of its original MODX textarea and will auto overflow
  -- "Infiinity" - editor will keep growing according to the height of its content.
*/


codeMirrorTextareasIDorCLASS = [
  {
    "name": "introtext",
    "selector": "#modx-resource-introtext",
    "mode": "html",
    "wrap": "free",
    "maxHeight": "textarea",
  },
  {
    "name": "description",
    "selector": "#modx-resource-description",
    "mode": "html",
    "wrap": "free",
    "maxHeight": "textarea"
  },
  {
    "name": "richtext",
    "selector": ".modx-richtext",
    "mode": "html",
    "wrap": "free",
    "maxHeight": 200,
  },
  {
    "name": "resource_content",
    "selector": "#ta",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "chunk",
    "selector": "#modx-chunk-snippet",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "template",
    "selector": "#modx-template-content",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "snippet",
    "selector": "#modx-snippet-snippet",
    "mode": "auto/php",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "plugin",
    "selector": "#modx-plugin-plugincode",
    "mode": "auto/php",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "file",
    "selector": "#modx-file-content",
    "mode": "auto/css",
    "wrap": "free",
    "maxHeight": "Infinity",
  }
];

// all on-the-fly textareas attached to mouseenter event (e.g Quick update/create textareas)
codeMirrorFlyTextareasIDorCLASS = [
  {
    "selector": "textarea[name=snippet]",
    "mode": "php",
    "wrap": "free",
    "maxHeight": "Infinity",
  }, {
    "selector": "textarea[name=content]",
    "mode": "html",
    "wrap": "free",
    "maxHeight": "Infinity",
  }, {
    "selector": "textarea[name=plugincode]",
    "mode": "php",
    "wrap": "free",
    "maxHeight": "Infinity",
  }
];

//extend if you like
modxCodeMirrorMimeTypes = {
  \'text/html\': \'html\',
  \'text/plain\': \'text\',
  \'text/css\': \'css\',
  \'text/javascript\': \'javascript\',
  \'application/javascript\': \'javascript\',
  \'text/x-less\': \'less\',
  \'application/xml\': \'xml\',
  \'text/xml\': \'xml\',
  \'application/json\': \'json\',
  \'text/x-markdown\': \'markdown\'
};


// actual CodeMirror Init configuration (get all from official CodeMirror site, oh yea - total freedom!!!)
codeMirrorInnerInit = function(mode, theme, maxLines, wrap) {
  var viewportMargin = viewportMargin ? viewportMargin : Infinity;
  var inner = {
      mode: mode,
      [[+CodeMirrorTHEME:notempty=`theme: "[[+CodeMirrorTHEME]]",`]]
      indentOnInit: true,
      lineNumbers: true,
      lineWrapping: true,
      foldGutter: true,
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
      indentUnit: 1,
      tabSize: 1,
      matchBrackets: true,
      styleActiveLine: true,
      autoCloseTags: true,
      showTrailingSpace: true,
      viewportMargin: viewportMargin,
      extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Alt-F": "findPersistent",
        "F11": function(cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        },
        "Ctrl-Q": function(cm){
          cm.foldCode(cm.getCursor());
         }
      }
    }
  return inner;
};

/*#######################################################################################
************************** abandon all fear - all ye who enter **************************
#######################################################################################*/

modxCodeMirrorInit = function (elem, mode, spec, maxLines, wrap, realFly){
  var originalTextarea = document.getElementById(elem);
  var maxHeightCodeMirror = maxLines;
  originalTextarea.style.display  = "none";
  if(isNaN(maxHeightCodeMirror)){
    var height = $(originalTextarea).height();
  }
  else{
    if(maxHeightCodeMirror == "Infinity"){
      var height = "auto";
    }
    else{
      var height = maxHeightCodeMirror;
    }
  }
  var spanMode = mode;
  if(mode == "null" || !mode){
    var mode = "null";
    var spanMode = "text/plain";
  }
  if(mode == "html" || mode == "htm"){
    var mode = "htmlmixed";
    var spec = "text/html";
  }
  var codeMirrorTempDiv =
    $(\'<div>\', {
      \'id\': \'codeMirror_wrapper_\'+originalTextarea.id,
      \'class\': \'codeMirror_wrapper_modx \'+realFly,
      \'data-codemirror\': \'codeMirror_id_\'+originalTextarea.id,
      \'data-origin\': originalTextarea.id,
      \'data-mode\': mode,
      \'data-maxL\': maxLines,
      \'data-wrap\': wrap
    }).prepend(
      $(\'<span>\', {
        \'class\': \'codeMirrorKitchenSink\',
        \'text\': spanMode,
        \'title\': \'maxLine: \'+maxLines+\'... wrap: \'+wrap
      }),
      $(\'<textarea>\', {
        \'id\': \'codeMirror_id_\'+originalTextarea.id,
        \'width\': "auto",
        \'height\': height ? height : "",
        \'data-origin\': originalTextarea.id
      })
    ).hide().insertBefore(originalTextarea);

  window["codeMirror_id_" + originalTextarea.id] = CodeMirror.fromTextArea(document.getElementById(\'codeMirror_id_\'+originalTextarea.id), codeMirrorInnerInit(spec));
  // if(mode == "htmlmixed"){
    // window["codeMirror_id_" + originalTextarea.id].setOption("profile","xhtml");
    emmetCodeMirror(window["codeMirror_id_" + originalTextarea.id]);
  // }
  CodeMirror.autoLoadMode(window["codeMirror_id_" + originalTextarea.id], mode);
  window["codeMirror_id_" + originalTextarea.id].setValue(originalTextarea.value);
  $(\'#codeMirror_wrapper_\'+originalTextarea.id).delay(100).fadeIn();
  $(\'#codeMirror_wrapper_\'+originalTextarea.id).find(".CodeMirror").css("height",height)
  setTimeout(function(){
    window["codeMirror_id_" + originalTextarea.id].refresh();
  },250)
  codeMirrorSetContentsilentMODX = false;
  window["codeMirror_id_" + originalTextarea.id].on("focus", function() {
    if(typeof tinymce !== "undefined" && tinymce.get(elem)){
      tinymce.get(elem).on("keyup change", function() {
        if($("#codeMirror_id_"+originalTextarea.id).length){
          codeMirrorSetContentsilentMODX = true;
          window["codeMirror_id_" + originalTextarea.id].setValue(tinymce.get(elem).getContent());
          codeMirrorSetContentsilentMODX = false;
        }
      })
    }
  })
  window["codeMirror_id_" + originalTextarea.id].on("change", function() {
    if (codeMirrorSetContentsilentMODX){
      return;
    }
    else{
      originalTextarea.value = window["codeMirror_id_" + originalTextarea.id].getValue();
      if(typeof tinymce !== "undefined" && tinymce.get(elem)){
        tinymce.get(elem).setContent(window["codeMirror_id_" + originalTextarea.id].getValue());
      }
    }
  })
  $(originalTextarea).parents(".modx-tv").find(".modx-tv-reset").on("click", function(){
    setTimeout(function(){
      window["codeMirror_id_" + originalTextarea.id].setValue(originalTextarea.value);
    },200)
  });
  $(originalTextarea).on("keyup change", function() {
    codeMirrorSetContentsilentMODX = true;
    window["codeMirror_id_" + originalTextarea.id].setValue($(this).val());
    codeMirrorSetContentsilentMODX = false;
   })
}
function changeMyButton(thisButton,id,mode,spec,maxLines,wrap,realFly){
  if($(thisButton).hasClass("codeMirrorActive")){
    if($("#"+id).is(\':visible\') ){
      $(thisButton).find("button").text("Remove CodeMirror");
      $(thisButton).removeClass("codeMirrorActive");
      modxCodeMirrorInit(id,mode,spec,maxLines,wrap,realFly)
    }
  }
  else{
    $(thisButton).addClass("codeMirrorActive");
    $(thisButton).find("button").text("Edit With CodeMirror");
    var thisCodeMirrorId = $(thisButton).parents(".modx-window").find(".codeMirror_wrapper_modx").data("codemirror");
    window[thisCodeMirrorId].toTextArea();
    $("#codeMirror_wrapper_"+id).remove();
    $("#"+id).fadeIn();
  }
}

function splitMyJoint(val,joint,index) {
  var item = val.split(joint);
  return item[index];
}

function getCodeMirrorCredential() {
  var thisWrap = $("#codeMirrorMoreSuperOptions_wrapper").parent();
  var thisSink = thisWrap.find(".codeMirrorKitchenSink");
  var thisActualCodeMirror = thisWrap.find(".CodeMirror");
  var originalTextareaId = thisWrap.data("origin");
  var thisCodeMirrorId = thisWrap.data("codemirror");
  return {
      thisWrap: thisWrap,
      thisSink: thisSink,
      originalTextareaId: originalTextareaId,
      thisCodeMirrorId: thisCodeMirrorId,
      thisActualCodeMirror: thisActualCodeMirror
    }
}

function autoDetectMode(filePathOrModeOrMime) {
  var val = filePathOrModeOrMime, m, mode, spec;
  if (m = /.+\\.([^.]+)$/.exec(val)) {
    var info = CodeMirror.findModeByExtension(m[1]);
    if (info) {
      mode = info.mode;
      spec = info.mime;
    }
  }
  else if (/\\//.test(val)) {
    var info = CodeMirror.findModeByMIME(val);
    if (info) {
      mode = info.mode;
      spec = val;
    }
  }
  else {
    mode = spec = val;
  }
  return {
    mode: mode,
    spec: spec
  };
}

function codeMirrorTextareas(){
  var mainCSScodeMirror = \'<style id=mainCSScodeMirror>.x-tab-panel,.x-window-body{overflow-x:hidden!important}#codeMirrorMoreSuperOptions_wrapper{position:absolute!important;z-index:9!important;right:10px;top:-20px}#codeMirrorMoreSuperOptions_wrapper .mce-listbox button{padding-right:10px!important}#codeMirrorMoreSuperOptions_wrapper .mce-btn-has-text .mce-ico{padding-right:0!important}#codeMirrorMoreSuperOptions_wrapper.codeMirror_editor_fullScreen{position:fixed!important;right:23px}#codeMirrorMoreSuperOptions_wrapper_boss{float:right;cursor:pointer}#codeMirrorMoreSuperOptions_wrapper_boss button{padding:0}#codeMirrorMoreSuperOptions_wrapper_sub{margin-top:24px}.codeMirror_wrapper_modx.codeMirror_editor_fullScreen{position:fixed;top:0;width:100%;height:100%;left:0;right:0;bottom:0;z-index:26;background:#fff;overflow:auto}.codeMirrorKitchenSink{position:absolute;background: #fff;color: #000;padding: 0px 6px;border-radius: 8px 0 8px 0;top: -20px;right:30px;z-index:9;font-size:12px;font-weight:bold;border:1px solid #808080;font-style:italic;}.twCodeMirrorParent .CodeMirror{height:auto}</style>\';
  if(!$("head #mainCSScodeMirror").length){
    $(\'head\').append(mainCSScodeMirror);
  }
  //all textareas ... that are present on page load
  $.each(codeMirrorTextareasIDorCLASS, function() {
    if($(this.selector).length){
      $(this.selector).parent().addClass("twCodeMirrorParent");
      var twCodeMirrorRealOrFly = "twCodeMirrorReal";
      var elemCodeMirror = $(this.selector);
      var ext = "test."+this.mode;
      var mode = autoDetectMode(ext).mode;
      var spec = autoDetectMode(ext).spec;
      var height = this.maxHeight;
      var wrap = this.wrap;
      if(this.name == "resource_content" && this.mode.indexOf("auto/") > -1){
        var mode = autoDetectMode("test."+splitMyJoint(this.mode,"/",1)).mode;
        var spec = autoDetectMode("test."+splitMyJoint(this.mode,"/",1)).spec;
        if(typeof twGetResourceContentType !== "undefined" && twGetResourceContentType !== ""){
          var mode = autoDetectMode(twGetResourceContentType).mode; //autoDetectMode value is supplied by serverside plugin
          var spec = autoDetectMode(twGetResourceContentType).spec;
        }
      }
      else if((this.name == "chunk" || this.name == "snippet" || this.name == "plugin" || this.name == "template") && this.mode.indexOf("auto/") > -1){
        var mode = splitMyJoint(this.mode,"/",1);
        if($("#modx-"+this.name+"-static-file").length && $("#modx-"+this.name+"-static-file").val() !== ""){
          var filePath = $("#modx-"+this.name+"-static-file").val();
          var mode = autoDetectMode(filePath).mode;
          var spec = autoDetectMode(filePath).spec;
        }
      }
      else if(this.name == "file" && this.mode.indexOf("auto/") > -1){
        var filePath = $("#modx-file-name").val();
        var mode = autoDetectMode(filePath).mode;
        var spec = autoDetectMode(filePath).spec;
      }
      $(this.selector).each(function(){ // for the sake of .modx-richtext and other classnames provided
        var elemCodeMirrorId = $(this).attr("id");
        modxCodeMirrorInit(elemCodeMirrorId,mode,spec,height,wrap,twCodeMirrorRealOrFly);
      })
    }
  });

  //all textareas added dynamically to the DOM ... quick create/update and anything else
  $(document).on("mouseenter", ".modx-window", function () {
    $this = $(this);
    $.each(codeMirrorFlyTextareasIDorCLASS, function() {
      if($this.find(this.selector).length){
        var thisTextarea = $this.find(this.selector);
        thisTextarea.parent().addClass("twCodeMirrorParent");
        twElemCodeMirrorId = thisTextarea.attr("id");
        twCodeMirrorMode = autoDetectMode(this.mode).mode;
        twCodeMirrorSpec = autoDetectMode(this.mode).spec;
        twCodeMirrorRealOrFly = "twCodeMirrorQuickFly";
        if($this.find("input[name=path]").length){ // for quick edit existing files
          twCodeMirrorMode = $this.find("input[name=path]").val()
          twCodeMirrorMode = autoDetectMode(twCodeMirrorMode).mode;
          twCodeMirrorSpec = autoDetectMode(twCodeMirrorMode).spec;
        }
        twCodeMirrorHeight = this.maxHeight;
        twCodeMirrorWrap = this.wrap;
        if(thisTextarea.is(\':visible\') && $this.find("#codeMirror_wrapper_"+twElemCodeMirrorId).length < 1 && !$this.find(".tinyCM").length){
        // modxCodeMirrorInit(twElemCodeMirrorId,twCodeMirrorMode,twCodeMirrorSpec,twCodeMirrorHeight,twCodeMirrorWrap,twCodeMirrorRealOrFly) // to fire this automatically, comment the bellow
          $this.find(".x-toolbar-left-row").append("<p onclick=changeMyButton(this,twElemCodeMirrorId,twCodeMirrorMode,twCodeMirrorSpec,twCodeMirrorHeight,twCodeMirrorWrap,twCodeMirrorRealOrFly); class=\\\'tinyCM codeMirrorActive x-btn x-btn-small x-btn-icon-small-left x-btn-noicon\\\' unselectable=\\\'on\\\'><em><button class=\\\'x-btn-text\\\'>Edit with CodeMirror</button></em></p>");
        }
      }
    });
  });
  if (typeof codeMirrorMoreSuperOptions == \'function\') { 
    codeMirrorMoreSuperOptions();
  }
}

/*#######################################################################################
     *********** Begin Kitchen Sink  (plcodeMirror in chunk or external file ***********
#######################################################################################*/
// font size / themes / height etc
function setCodeMirrorTheme(){
  var theme = this.text();
  var link = document.createElement(\'link\');
  link.onload = function(){
      window[getCodeMirrorCredential().thisCodeMirrorId].setOption("theme", theme);
  };
  link.rel = "stylesheet";
  link.media = "all";
  link.href = "[[+CodeMirrorSrc]]theme/"+theme+".css";
  document.getElementsByTagName(\'head\')[0].appendChild(link);
}
// kitchen sink
function setCodeMirrorSetFont(){
  getCodeMirrorCredential().thisActualCodeMirror.css("font-size",this.text());
  window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
}
// kitchen sink
function setCodeMirrorSetWrap(){
  if(this.text() == "free"){
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", "");
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
  else{
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize(+this.text()+290, "");
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
}
// kitchen sink
function setCodeMirrorSetHeight(){
  var thisTextarea = getCodeMirrorCredential().originalTextareaId;
  var option = this.text();
  if(option == "Textarea"){
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", $("#"+thisTextarea).height());
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
  else if(option == "1"){
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", 40);
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
  else if(option == "10"){
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", 200);
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
  else {
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", "");
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
}


// kitchen sink
// mode / syntax / language -- add as many as you like
codeMirrorMoreSuperOptionsToolButtonMODE =[
  {text: \'--- MOST COMMON ---\', value: \'\', disabled: true, selected: false, active: false},
  {text: \'HTML\', value: \'htmlmixed\'},
  {text: \'Markdown\', value: \'markdown\'},
  {text: \'CSS\', value: \'css\'},
  {text: \'PHP\', value: \'php\'},
  {text: \'JavaScript\', value: \'javascript\'},
  {text: \'JSON\', value: \'javascript\'},
  {text: \'Text\', value: \'null\'},
  {text: \'LESS\', value: \'css\'},
  {text: \'SASS\', value: \'sass\'},
  {text: \'SCSS\', value: \'css\'},
  {text: \'--- ALL SYNTAX ---\', value: \'\', disabled: true, selected: false, active: false},
  {text: \'APL\', value: \'apl\'},
  {text: \'PGP\', value: \'asciiarmor\'},
  {text: \'ASN.1\', value: \'asn.1\'},
  {text: \'Asterisk\', value: \'asterisk\'},
  {text: \'Brainfuck\', value: \'brainfuck\'},
  {text: \'C\', value: \'clike\'},
  {text: \'C++\', value: \'clike\'},
  {text: \'Cobol\', value: \'cobol\'},
  {text: \'C#\', value: \'clike\'},
  {text: \'Clojure\', value: \'clojure\'},
  {text: \'ClojureScript\', value: \'clojure\'},
  {text: \'Closure Stylesheets (GSS)\', value: \'css\'},
  {text: \'CMake\', value: \'cmake\'},
  {text: \'CoffeeScript\', value: \'coffeescript\'},
  {text: \'Common Lisp\', value: \'commonlisp\'},
  {text: \'Cypher\', value: \'cypher\'},
  {text: \'Cython\', value: \'python\'},
  {text: \'Crystal\', value: \'crystal\'},
  {text: \'CSS\', value: \'css\'},
  {text: \'CQL\', value: \'sql\'},
  {text: \'D\', value: \'d\'},
  {text: \'Dart\', value: \'dart\'},
  {text: \'diff\', value: \'diff\'},
  {text: \'Django\', value: \'django\'},
  {text: \'Dockerfile\', value: \'dockerfile\'},
  {text: \'DTD\', value: \'dtd\'},
  {text: \'Dylan\', value: \'dylan\'},
  {text: \'EBNF\', value: \'ebnf\'},
  {text: \'ECL\', value: \'ecl\'},
  {text: \'edn\', value: \'clojure\'},
  {text: \'Eiffel\', value: \'eiffel\'},
  {text: \'Elm\', value: \'elm\'},
  {text: \'Embedded Javascript\', value: \'htmlembedded\'},
  {text: \'Embedded Ruby\', value: \'htmlembedded\'},
  {text: \'Erlang\', value: \'erlang\'},
  {text: \'Factor\', value: \'factor\'},
  {text: \'FCL\', value: \'fcl\'},
  {text: \'Forth\', value: \'forth\'},
  {text: \'Fortran\', value: \'fortran\'},
  {text: \'F#\', value: \'mllike\'},
  {text: \'Gas\', value: \'gas\'},
  {text: \'Gherkin\', value: \'gherkin\'},
  {text: \'GitHub Flavored Markdown\', value: \'gfm\'},
  {text: \'Go\', value: \'go\'},
  {text: \'Groovy\', value: \'groovy\'},
  {text: \'HAML\', value: \'haml\'},
  {text: \'Haskell\', value: \'haskell\'},
  {text: \'Haskell (Literate)\', value: \'haskell-literate\'},
  {text: \'Haxe\', value: \'haxe\'},
  {text: \'HXML\', value: \'haxe\'},
  {text: \'ASP.NET\', value: \'htmlembedded\'},
  {text: \'HTML\', value: \'htmlmixed\'},
  {text: \'HTTP\', value: \'http\'},
  {text: \'IDL\', value: \'idl\'},
  {text: \'Jade\', value: \'jade\'},
  {text: \'Java\', value: \'clike\'},
  {text: \'Java Server Pages\', value: \'htmlembedded\'},
  {name: \'JavaScript\', value: \'javascript\'},
  {text: \'JSON\', value: \'javascript\'},
  {text: \'JSON-LD\', value: \'javascript\'},
  {text: \'JSX\', value: \'jsx\'},
  {text: \'Jinja2\', value: \'jinja2\'},
  {text: \'Julia\', value: \'julia\'},
  {text: \'Kotlin\', value: \'clike\'},
  {text: \'LESS\', value: \'css\'},
  {text: \'LiveScript\', value: \'livescript\'},
  {text: \'Lua\', value: \'lua\'},
  {text: \'Markdown\', value: \'markdown\'},
  {text: \'mIRC\', value: \'mirc\'},
  {text: \'MariaDB SQL\', value: \'sql\'},
  {text: \'Mathematica\', value: \'mathematica\'},
  {text: \'Modelica\', value: \'modelica\'},
  {text: \'MUMPS\', value: \'mumps\'},
  {text: \'MS SQL\', value: \'sql\'},
  {text: \'MySQL\', value: \'sql\'},
  {text: \'Nginx\', value: \'nginx\'},
  {text: \'NSIS\', value: \'nsis\'},
  {text: \'NTriples\', value: \'ntriples\'},
  {text: \'Objective C\', value: \'clike\'},
  {text: \'OCaml\', value: \'mllike\'},
  {text: \'Octave\', value: \'octave\'},
  {text: \'Oz\', value: \'oz\'},
  {text: \'Pascal\', value: \'pascal\'},
  {text: \'PEG.js\', value: \'pegjs\'},
  {text: \'Perl\', value: \'perl\'},
  {text: \'PHP\', value: \'php\'},
  {text: \'Pig\', value: \'pig\'},
  {text: \'Plain Text\', value: \'null\'},
  {text: \'PLSQL\', value: \'sql\'},
  {text: \'PowerShell\', value: \'powershell\'},
  {text: \'Properties files\', value: \'properties\'},
  {text: \'ProtoBuf\', value: \'protobuf\'},
  {text: \'Python\', value: \'python\'},
  {text: \'Puppet\', value: \'puppet\'},
  {text: \'Q\', value: \'q\'},
  {text: \'R\', value: \'r\'},
  {text: \'reStructuredText\', value: \'rst\'},
  {text: \'RPM Changes\', value: \'rpm\'},
  {text: \'RPM Spec\', value: \'rpm\'},
  {text: \'Ruby\', value: \'ruby\'},
  {text: \'Rust\', value: \'rust\'},
  {text: \'SAS\', value: \'sas\'},
  {text: \'Sass\', value: \'sass\'},
  {text: \'Scala\', value: \'clike\'},
  {text: \'Scheme\', value: \'scheme\'},
  {text: \'SCSS\', value: \'css\'},
  {text: \'Shell\', value: \'shell\'},
  {text: \'Sieve\', value: \'sieve\'},
  {text: \'Slim\', value: \'slim\'},
  {text: \'Smalltalk\', value: \'smalltalk\'},
  {text: \'Smarty\', value: \'smarty\'},
  {text: \'Solr\', value: \'solr\'},
  {text: \'Soy\', value: \'soy\'},
  {text: \'SPARQL\', value: \'sparql\'},
  {text: \'Spreadsheet\', value: \'spreadsheet\', alias: [\'excel\', \'formula\']},
  {text: \'SQL\', value: \'sql\'},
  {text: \'Squirrel\', value: \'clike\'},
  {text: \'Swift\', value: \'swift\'},
  {text: \'sTeX\', value: \'stex\'},
  {text: \'LaTeX\', value: \'stex\'},
  {text: \'SystemVerilog\', value: \'verilog\'},
  {text: \'Tcl\', value: \'tcl\'},
  {text: \'Textile\', value: \'textile\'},
  {text: \'TiddlyWiki \', value: \'tiddlywiki\'},
  {text: \'Tiki wiki\', value: \'tiki\'},
  {text: \'TOML\', value: \'toml\'},
  {text: \'Tornado\', value: \'tornado\'},
  {text: \'troff\', value: \'troff\'},
  {text: \'TTCN\', value: \'ttcn\'},
  {text: \'TTCN_CFG\', value: \'ttcn-cfg\'},
  {text: \'Turtle\', value: \'turtle\'},
  {text: \'TypeScript\', value: \'javascript\'},
  {text: \'Twig\', value: \'twig\'},
  {text: \'Web IDL\', value: \'webidl\'},
  {text: \'VB.NET\', value: \'vb\'},
  {text: \'VBScript\', value: \'vbscript\'},
  {text: \'Velocity\', value: \'velocity\'},
  {text: \'Verilog\', value: \'verilog\'},
  {text: \'VHDL\', value: \'vhdl\'},
  {text: \'XML\', value: \'xml\'},
  {text: \'XQuery\', value: \'xquery\'},
  {text: \'Yacas\', value: \'yacas\'},
  {text: \'YAML\', value: \'yaml\'},
  {text: \'Z80\', value: \'z80\'},
  {text: \'mscgen\', value: \'mscgen\'},
  {text: \'xu\', value: \'mscgen\'},
  {text: \'msgenny\', value: \'mscgen\'}
];

// kitchen sink
// change font size on the fly
codeMirrorMoreSuperOptionsToolButtonFONT =[
  {text: \'10px\', onclick: setCodeMirrorSetFont},
  {text: \'11px\', onclick: setCodeMirrorSetFont},
  {text: \'12px\', onclick: setCodeMirrorSetFont},
  {text: \'13px\', onclick: setCodeMirrorSetFont},
  {text: \'14px\', onclick: setCodeMirrorSetFont},
  {text: \'15px\', onclick: setCodeMirrorSetFont},
  {text: \'16px\', onclick: setCodeMirrorSetFont},
  {text: \'18px\', onclick: setCodeMirrorSetFont},
  {text: \'20px\', onclick: setCodeMirrorSetFont},
  {text: \'24px\', onclick: setCodeMirrorSetFont}
];

// kitchen sink
// change wrap style
codeMirrorMoreSuperOptionsToolButtonWRAP =[
  {text: \'free\', onclick: setCodeMirrorSetWrap},
  {text: \'40\', onclick: setCodeMirrorSetWrap},
  {text: \'180\', onclick: setCodeMirrorSetWrap}
];

// kitchen sink
// change height/max lines
codeMirrorMoreSuperOptionsToolButtonHEIGHT =[
  {text: \'1\', onclick: setCodeMirrorSetHeight},
  {text: \'10\', onclick: setCodeMirrorSetHeight},
  {text: \'Infinity\', onclick: setCodeMirrorSetHeight},
  {text: \'Textarea\', onclick: setCodeMirrorSetHeight}
];

// kitchen sink
// change theme on the fly
codeMirrorMoreSuperOptionsToolButtonTHEME = [
  {text: \'3024-day\', onclick: setCodeMirrorTheme},
  {text: \'3024-night\', onclick: setCodeMirrorTheme},
  {text: \'abcdef\', onclick: setCodeMirrorTheme},
  {text: \'ambiance\', onclick: setCodeMirrorTheme},
  {text: \'base16-dark\', onclick: setCodeMirrorTheme},
  {text: \'base16-light\', onclick: setCodeMirrorTheme},
  {text: \'bespin\', onclick: setCodeMirrorTheme},
  {text: \'blackboard\', onclick: setCodeMirrorTheme},
  {text: \'cobalt\', onclick: setCodeMirrorTheme},
  {text: \'colorforth\', onclick: setCodeMirrorTheme},
  {text: \'dracula\', onclick: setCodeMirrorTheme},
  {text: \'eclipse\', onclick: setCodeMirrorTheme},
  {text: \'elegant\', onclick: setCodeMirrorTheme},
  {text: \'erlang-dark\', onclick: setCodeMirrorTheme},
  {text: \'hopscotch\', onclick: setCodeMirrorTheme},
  {text: \'icecoder\', onclick: setCodeMirrorTheme},
  {text: \'isotope\', onclick: setCodeMirrorTheme},
  {text: \'lesser-dark\', onclick: setCodeMirrorTheme},
  {text: \'liquibyte\', onclick: setCodeMirrorTheme},
  {text: \'material\', onclick: setCodeMirrorTheme},
  {text: \'mbo\', onclick: setCodeMirrorTheme},
  {text: \'mdn-like\', onclick: setCodeMirrorTheme},
  {text: \'midnight\', onclick: setCodeMirrorTheme},
  {text: \'monokai\', onclick: setCodeMirrorTheme},
  {text: \'neat\', onclick: setCodeMirrorTheme},
  {text: \'neo\', onclick: setCodeMirrorTheme},
  {text: \'night\', onclick: setCodeMirrorTheme},
  {text: \'paraiso-dark\', onclick: setCodeMirrorTheme},
  {text: \'paraiso-light\', onclick: setCodeMirrorTheme},
  {text: \'pastel-on-dark\', onclick: setCodeMirrorTheme},
  {text: \'railscasts\', onclick: setCodeMirrorTheme},
  {text: \'rubyblue\', onclick: setCodeMirrorTheme},
  {text: \'seti\', onclick: setCodeMirrorTheme},
  {text: \'solarized dark\', onclick: setCodeMirrorTheme},
  {text: \'solarized light\', onclick: setCodeMirrorTheme},
  {text: \'the-matrix\', onclick: setCodeMirrorTheme},
  {text: \'tomorrow-night-bright\', onclick: setCodeMirrorTheme},
  {text: \'tomorrow-night-eighties\', onclick: setCodeMirrorTheme},
  {text: \'ttcn\', onclick: setCodeMirrorTheme},
  {text: \'twilight\', onclick: setCodeMirrorTheme},
  {text: \'vibrant-ink\', onclick: setCodeMirrorTheme},
  {text: \'xq-dark\', onclick: setCodeMirrorTheme},
  {text: \'xq-light\', onclick: setCodeMirrorTheme},
  {text: \'yeti\', onclick: setCodeMirrorTheme},
  {text: \'zenburn\', onclick: setCodeMirrorTheme}
];

// kitchen sink toolbar ini
function codeMirrorMoreSuperOptions(){
  if(typeof tinymce !== "undefined" && !$("#codeMirrorMoreSuperOptions_wrapper").length){
    $("body").append("<div id=codeMirrorMoreSuperOptions_wrapper><div id=codeMirrorMoreSuperOptions_wrapper_boss></div><div style=display:none; id=codeMirrorMoreSuperOptions_wrapper_sub></div></div>");
    tinymce.ui.Factory.create({
      type: "button",
      icon: "fullpage",
      classes:"moreCodeMirrorAttr",
      tooltip: "CodeMirror settings...",
      onPostRender:function(){
        $(".mce-moreCodeMirrorAttr").attr("class","mce-moreCodeMirrorAttr");
        $(".mce-moreCodeMirrorAttr button").contents().unwrap();
      },
      onclick: function(){
        if($(\'#codeMirrorMoreSuperOptions_wrapper_sub:hidden\').length){
          $(\'#codeMirrorMoreSuperOptions_wrapper_sub\').show()
          $(\'.mce-moreCodeMirrorAttr\').addClass(\'mce-active\')
        }
        else{
          $(\'#codeMirrorMoreSuperOptions_wrapper_sub\').hide()
          $(\'.mce-moreCodeMirrorAttr\').removeClass(\'mce-active\')
        }
      }
    }).renderTo(document.getElementById("codeMirrorMoreSuperOptions_wrapper_boss"));
    tinymce.ui.Factory.create({
      type: \'button\',
      icon: \'fullscreen\',
      classes: \'codeMirrorMoreSuperOptionsFS\',
      tooltip: \'Fullscreen\',
      onclick: function(){
        if(getCodeMirrorCredential().thisWrap.hasClass("codeMirror_editor_fullScreen")){
          getCodeMirrorCredential().thisWrap.removeClass("codeMirror_editor_fullScreen");
          $("#codeMirrorMoreSuperOptions_wrapper").removeClass("codeMirror_editor_fullScreen");
          window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
        }
        else{
          getCodeMirrorCredential().thisWrap.addClass("codeMirror_editor_fullScreen")
          $("#codeMirrorMoreSuperOptions_wrapper").addClass("codeMirror_editor_fullScreen");
          window[getCodeMirrorCredential().thisCodeMirrorId].refresh()
        }
      },
      onPostRender:function(){
        // $(".mce-codeMirrorMoreSuperOptions .mce-caret").remove();
        $(document).on("mouseenter click", ".codeMirror_wrapper_modx", function () {
          if(!$(this).find("#codeMirrorMoreSuperOptions_wrapper").length){
            $("#codeMirrorMoreSuperOptions_wrapper").prependTo($(this)).fadeIn();
            codeMirrorMoreSuperOptions();
          }
        })
      }
    }).renderTo(document.getElementById("codeMirrorMoreSuperOptions_wrapper_sub"));
    tinymce.ui.Factory.create({
      type: \'listbox\',
      icon: \'code\',
      classes: \'codeMirrorMoreSuperOptionsMODE\',
      tooltip: \'Syntax / Mode\',
      menu: codeMirrorMoreSuperOptionsToolButtonMODE,
      onPostRender: function(){
        $(".mce-codeMirrorMoreSuperOptionsMODE .mce-caret").remove();
        $(".mce-codeMirrorMoreSuperOptionsMODE .mce-txt").remove();
      },
      onselect: function(){
        window[getCodeMirrorCredential().thisCodeMirrorId].setOption("mode", this.value())
        CodeMirror.autoLoadMode(window[getCodeMirrorCredential().thisCodeMirrorId], this.value());
        window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
        getCodeMirrorCredential().thisSink.text(this.value())
        $(".mce-codeMirrorMoreSuperOptionsMODE .mce-txt").remove();
      }
    }).renderTo(document.getElementById("codeMirrorMoreSuperOptions_wrapper_sub"));

    tinymce.ui.Factory.create({
      type: \'menubutton\',
      classes: \'codeMirrorMoreSuperOptionsMORE\',
      tooltip: \'Themes and more Options\',
      onPostRender:function(){
        // $(".mce-codeMirrorMoreSuperOptionsMORE .mce-caret").remove();
      },
      menu: [
      // {
      //   text: "Show Invisibles",
      //   classes: "setShowInvisibles",
      //   onclick: function(){
      //     if(window[getCodeMirrorCredential().thisCodeMirrorId].getOption("showInvisibles")){
      //       window[getCodeMirrorCredential().thisCodeMirrorId].setShowInvisibles(false);
      //     }
      //     else{
      //       window[getCodeMirrorCredential().thisCodeMirrorId].setShowInvisibles(true);
      //     }
      //   }
      // },
      {
        text: "Refresh Editor",
        onclick: function(){
          window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
        }
      },
      {
        text: "Soft Wrap",
        menu: codeMirrorMoreSuperOptionsToolButtonWRAP
      },
      {
        text: "Max Height",
        menu: codeMirrorMoreSuperOptionsToolButtonHEIGHT
      },
      {
        text: "Theme",
        menu: codeMirrorMoreSuperOptionsToolButtonTHEME
      },
      {
        text: "Font Size",
        menu: codeMirrorMoreSuperOptionsToolButtonFONT,
      },
      ],
    }).renderTo(document.getElementById("codeMirrorMoreSuperOptions_wrapper_sub"));
    if(tinymce.editors.length < 1){
      // fileManagerTopNavModalSkin = MODx.config.assets_url+"components/tinymcewrapper/tinymceskins/modxPericles/"+"skin.min.css";
      tinymce.DOM.loadCSS(fileManagerTopNavModalSkin+"/skin.min.css");
    }
  }
}
// ************************** END Kitchen Sink **************************


/*#######################################################################################
   *************** MODX INIT : load applicable JS and CSS files ***************
#######################################################################################*/
/*-------------------
more jargon underneath
load or not load what files you want to use
-----------------------------------------------------------*/


//load applicable CSS files
function loadcss(filename){
  var fileref=document.createElement("link")
  fileref.setAttribute("rel", "stylesheet")
  fileref.setAttribute("type", "text/css")
  fileref.setAttribute("href", filename)
  if (typeof fileref!="undefined"){
    document.getElementsByTagName("head")[0].appendChild(fileref);
   }
}

function loadThemCSS(){
  loadcss(\'[[+CodeMirrorSrc]]codemirror.min.css\');
  loadcss(\'[[+CodeMirrorSrc]]addon/dialog/dialog.css\');
  loadcss(\'[[+CodeMirrorSrc]]addon/search/matchesonscrollbar.css\');
  loadcss(\'[[+CodeMirrorSrc]]addon/display/fullscreen.css\');
  loadcss(\'[[+CodeMirrorSrc]]addon/fold/foldgutter.css\');
  [[+CodeMirrorTHEME:notempty=`loadcss(\'[[+CodeMirrorSrc]]theme/[[+CodeMirrorTHEME]].css\');`]]
}
loadThemCSS();
CodeMirror.modeURL = "[[+CodeMirrorSrc]]mode/%N/%N.js";

//load applicable JS files
Ext.Loader.load([
  \'[[++assets_url]]components/tinymcewrapper/tinymceplugins/codemirror/emmet.js\',
  \'[[+CodeMirrorSrc]]addon/wrap/hardwrap.min.js\',
  \'[[+CodeMirrorSrc]]addon/selection/active-line.min.js\',
  \'[[+CodeMirrorSrc]]addon/search/searchcursor.min.js\',
  \'[[+CodeMirrorSrc]]addon/scroll/annotatescrollbar.js\',
  \'[[+CodeMirrorSrc]]addon/search/matchesonscrollbar.js\',
  \'[[+CodeMirrorSrc]]addon/search/jump-to-line.min.js\',
  \'[[+CodeMirrorSrc]]addon/search/search.min.js\',
  \'[[+CodeMirrorSrc]]addon/dialog/dialog.min.js\',
  \'[[+CodeMirrorSrc]]addon/hint/show-hint.min.js\',
  \'[[+CodeMirrorSrc]]addon/hint/html-hint.min.js\',
  \'[[+CodeMirrorSrc]]addon/hint/xml-hint.min.js\',
  \'[[+CodeMirrorSrc]]addon/edit/closetag.min.js\',
  \'[[+CodeMirrorSrc]]addon/edit/trailingspace.min.js\',
  \'[[+CodeMirrorSrc]]addon/display/fullscreen.js\',
  \'[[+CodeMirrorSrc]]addon/fold/foldcode.js\',
  \'[[+CodeMirrorSrc]]addon/fold/foldgutter.js\',
  \'[[+CodeMirrorSrc]]addon/fold/brace-fold.js\',
  \'[[+CodeMirrorSrc]]addon/fold/xml-fold.js\',
  \'[[+CodeMirrorSrc]]addon/fold/comment-fold.js\',
  \'[[+CodeMirrorSrc]]addon/edit/matchbrackets.min.js\',
  \'[[+CodeMirrorSrc]]addon/lint/lint.js\',
  \'[[+CodeMirrorSrc]]addon/lint/css-lint.js\',
  \'[[+CodeMirrorSrc]]addon/lint/json-lint.js\',
  \'[[+CodeMirrorSrc]]addon/lint/javascript-lint.js\',
  \'[[+CodeMirrorSrc]]addon/lint/css-lint.js\',
  \'[[+CodeMirrorSrc]]addon/mode/loadmode.js\',
  \'[[+CodeMirrorSrc]]mode/meta.js\'
 ],
  function() {
   Ext.onReady(function () {
    codeMirrorTextareas();
   // })
   },this,{delay:50})
  },
  this,
  \'preserveOrder\'
);

emmetForCodeMirrorIsLoaded = true; // this helps other CodeMirror implementations to piggy-back on this emmet
',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '/*
#######################################################
TinymceWrapperCodeMirror is a chunk to let any whoever dominate the initialization of the powerful CodeMirror code editor.
This tiny config can work any where and with anything in MODX.
It will sync with TinyMCE if TinyMCE RTE is detected

Yours truly, donshakespeare @ MODX forums
#######################################################

##Language / Mode / Syntax
"mode": "html",
"mode": "auto/html",
  -- (elements) static source extension dependent - fallback to html
  -- (resources) content type (mimetype) dependent - fallsback to html

##max-height of editor in pixels
"maxHeight": pure integer / "textarea" / "Infinity",
  -- integer - e.g 100 (will make height 100px)
  -- "textarea" - editor will borrow the fixed height of its original MODX textarea and will auto overflow
  -- "Infiinity" - editor will keep growing according to the height of its content.
*/


codeMirrorTextareasIDorCLASS = [
  {
    "name": "introtext",
    "selector": "#modx-resource-introtext",
    "mode": "html",
    "wrap": "free",
    "maxHeight": "textarea",
  },
  {
    "name": "description",
    "selector": "#modx-resource-description",
    "mode": "html",
    "wrap": "free",
    "maxHeight": "textarea"
  },
  {
    "name": "richtext",
    "selector": ".modx-richtext",
    "mode": "html",
    "wrap": "free",
    "maxHeight": 200,
  },
  {
    "name": "resource_content",
    "selector": "#ta",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "chunk",
    "selector": "#modx-chunk-snippet",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "template",
    "selector": "#modx-template-content",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "snippet",
    "selector": "#modx-snippet-snippet",
    "mode": "auto/php",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "plugin",
    "selector": "#modx-plugin-plugincode",
    "mode": "auto/php",
    "wrap": "free",
    "maxHeight": "Infinity",
  },
  {
    "name": "file",
    "selector": "#modx-file-content",
    "mode": "auto/css",
    "wrap": "free",
    "maxHeight": "Infinity",
  }
];

// all on-the-fly textareas attached to mouseenter event (e.g Quick update/create textareas)
codeMirrorFlyTextareasIDorCLASS = [
  {
    "selector": "textarea[name=snippet]",
    "mode": "php",
    "wrap": "free",
    "maxHeight": "Infinity",
  }, {
    "selector": "textarea[name=content]",
    "mode": "html",
    "wrap": "free",
    "maxHeight": "Infinity",
  }, {
    "selector": "textarea[name=plugincode]",
    "mode": "php",
    "wrap": "free",
    "maxHeight": "Infinity",
  }
];

//extend if you like
modxCodeMirrorMimeTypes = {
  \'text/html\': \'html\',
  \'text/plain\': \'text\',
  \'text/css\': \'css\',
  \'text/javascript\': \'javascript\',
  \'application/javascript\': \'javascript\',
  \'text/x-less\': \'less\',
  \'application/xml\': \'xml\',
  \'text/xml\': \'xml\',
  \'application/json\': \'json\',
  \'text/x-markdown\': \'markdown\'
};


// actual CodeMirror Init configuration (get all from official CodeMirror site, oh yea - total freedom!!!)
codeMirrorInnerInit = function(mode, theme, maxLines, wrap) {
  var viewportMargin = viewportMargin ? viewportMargin : Infinity;
  var inner = {
      mode: mode,
      [[+CodeMirrorTHEME:notempty=`theme: "[[+CodeMirrorTHEME]]",`]]
      indentOnInit: true,
      lineNumbers: true,
      lineWrapping: true,
      foldGutter: true,
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
      indentUnit: 1,
      tabSize: 1,
      matchBrackets: true,
      styleActiveLine: true,
      autoCloseTags: true,
      showTrailingSpace: true,
      viewportMargin: viewportMargin,
      extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Alt-F": "findPersistent",
        "F11": function(cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        },
        "Ctrl-Q": function(cm){
          cm.foldCode(cm.getCursor());
         }
      }
    }
  return inner;
};

/*#######################################################################################
************************** abandon all fear - all ye who enter **************************
#######################################################################################*/

modxCodeMirrorInit = function (elem, mode, spec, maxLines, wrap, realFly){
  var originalTextarea = document.getElementById(elem);
  var maxHeightCodeMirror = maxLines;
  originalTextarea.style.display  = "none";
  if(isNaN(maxHeightCodeMirror)){
    var height = $(originalTextarea).height();
  }
  else{
    if(maxHeightCodeMirror == "Infinity"){
      var height = "auto";
    }
    else{
      var height = maxHeightCodeMirror;
    }
  }
  var spanMode = mode;
  if(mode == "null" || !mode){
    var mode = "null";
    var spanMode = "text/plain";
  }
  if(mode == "html" || mode == "htm"){
    var mode = "htmlmixed";
    var spec = "text/html";
  }
  var codeMirrorTempDiv =
    $(\'<div>\', {
      \'id\': \'codeMirror_wrapper_\'+originalTextarea.id,
      \'class\': \'codeMirror_wrapper_modx \'+realFly,
      \'data-codemirror\': \'codeMirror_id_\'+originalTextarea.id,
      \'data-origin\': originalTextarea.id,
      \'data-mode\': mode,
      \'data-maxL\': maxLines,
      \'data-wrap\': wrap
    }).prepend(
      $(\'<span>\', {
        \'class\': \'codeMirrorKitchenSink\',
        \'text\': spanMode,
        \'title\': \'maxLine: \'+maxLines+\'... wrap: \'+wrap
      }),
      $(\'<textarea>\', {
        \'id\': \'codeMirror_id_\'+originalTextarea.id,
        \'width\': "auto",
        \'height\': height ? height : "",
        \'data-origin\': originalTextarea.id
      })
    ).hide().insertBefore(originalTextarea);

  window["codeMirror_id_" + originalTextarea.id] = CodeMirror.fromTextArea(document.getElementById(\'codeMirror_id_\'+originalTextarea.id), codeMirrorInnerInit(spec));
  // if(mode == "htmlmixed"){
    // window["codeMirror_id_" + originalTextarea.id].setOption("profile","xhtml");
    emmetCodeMirror(window["codeMirror_id_" + originalTextarea.id]);
  // }
  CodeMirror.autoLoadMode(window["codeMirror_id_" + originalTextarea.id], mode);
  window["codeMirror_id_" + originalTextarea.id].setValue(originalTextarea.value);
  $(\'#codeMirror_wrapper_\'+originalTextarea.id).delay(100).fadeIn();
  $(\'#codeMirror_wrapper_\'+originalTextarea.id).find(".CodeMirror").css("height",height)
  setTimeout(function(){
    window["codeMirror_id_" + originalTextarea.id].refresh();
  },250)
  codeMirrorSetContentsilentMODX = false;
  window["codeMirror_id_" + originalTextarea.id].on("focus", function() {
    if(typeof tinymce !== "undefined" && tinymce.get(elem)){
      tinymce.get(elem).on("keyup change", function() {
        if($("#codeMirror_id_"+originalTextarea.id).length){
          codeMirrorSetContentsilentMODX = true;
          window["codeMirror_id_" + originalTextarea.id].setValue(tinymce.get(elem).getContent());
          codeMirrorSetContentsilentMODX = false;
        }
      })
    }
  })
  window["codeMirror_id_" + originalTextarea.id].on("change", function() {
    if (codeMirrorSetContentsilentMODX){
      return;
    }
    else{
      originalTextarea.value = window["codeMirror_id_" + originalTextarea.id].getValue();
      if(typeof tinymce !== "undefined" && tinymce.get(elem)){
        tinymce.get(elem).setContent(window["codeMirror_id_" + originalTextarea.id].getValue());
      }
    }
  })
  $(originalTextarea).parents(".modx-tv").find(".modx-tv-reset").on("click", function(){
    setTimeout(function(){
      window["codeMirror_id_" + originalTextarea.id].setValue(originalTextarea.value);
    },200)
  });
  $(originalTextarea).on("keyup change", function() {
    codeMirrorSetContentsilentMODX = true;
    window["codeMirror_id_" + originalTextarea.id].setValue($(this).val());
    codeMirrorSetContentsilentMODX = false;
   })
}
function changeMyButton(thisButton,id,mode,spec,maxLines,wrap,realFly){
  if($(thisButton).hasClass("codeMirrorActive")){
    if($("#"+id).is(\':visible\') ){
      $(thisButton).find("button").text("Remove CodeMirror");
      $(thisButton).removeClass("codeMirrorActive");
      modxCodeMirrorInit(id,mode,spec,maxLines,wrap,realFly)
    }
  }
  else{
    $(thisButton).addClass("codeMirrorActive");
    $(thisButton).find("button").text("Edit With CodeMirror");
    var thisCodeMirrorId = $(thisButton).parents(".modx-window").find(".codeMirror_wrapper_modx").data("codemirror");
    window[thisCodeMirrorId].toTextArea();
    $("#codeMirror_wrapper_"+id).remove();
    $("#"+id).fadeIn();
  }
}

function splitMyJoint(val,joint,index) {
  var item = val.split(joint);
  return item[index];
}

function getCodeMirrorCredential() {
  var thisWrap = $("#codeMirrorMoreSuperOptions_wrapper").parent();
  var thisSink = thisWrap.find(".codeMirrorKitchenSink");
  var thisActualCodeMirror = thisWrap.find(".CodeMirror");
  var originalTextareaId = thisWrap.data("origin");
  var thisCodeMirrorId = thisWrap.data("codemirror");
  return {
      thisWrap: thisWrap,
      thisSink: thisSink,
      originalTextareaId: originalTextareaId,
      thisCodeMirrorId: thisCodeMirrorId,
      thisActualCodeMirror: thisActualCodeMirror
    }
}

function autoDetectMode(filePathOrModeOrMime) {
  var val = filePathOrModeOrMime, m, mode, spec;
  if (m = /.+\\.([^.]+)$/.exec(val)) {
    var info = CodeMirror.findModeByExtension(m[1]);
    if (info) {
      mode = info.mode;
      spec = info.mime;
    }
  }
  else if (/\\//.test(val)) {
    var info = CodeMirror.findModeByMIME(val);
    if (info) {
      mode = info.mode;
      spec = val;
    }
  }
  else {
    mode = spec = val;
  }
  return {
    mode: mode,
    spec: spec
  };
}

function codeMirrorTextareas(){
  var mainCSScodeMirror = \'<style id=mainCSScodeMirror>.x-tab-panel,.x-window-body{overflow-x:hidden!important}#codeMirrorMoreSuperOptions_wrapper{position:absolute!important;z-index:9!important;right:10px;top:-20px}#codeMirrorMoreSuperOptions_wrapper .mce-listbox button{padding-right:10px!important}#codeMirrorMoreSuperOptions_wrapper .mce-btn-has-text .mce-ico{padding-right:0!important}#codeMirrorMoreSuperOptions_wrapper.codeMirror_editor_fullScreen{position:fixed!important;right:23px}#codeMirrorMoreSuperOptions_wrapper_boss{float:right;cursor:pointer}#codeMirrorMoreSuperOptions_wrapper_boss button{padding:0}#codeMirrorMoreSuperOptions_wrapper_sub{margin-top:24px}.codeMirror_wrapper_modx.codeMirror_editor_fullScreen{position:fixed;top:0;width:100%;height:100%;left:0;right:0;bottom:0;z-index:26;background:#fff;overflow:auto}.codeMirrorKitchenSink{position:absolute;background: #fff;color: #000;padding: 0px 6px;border-radius: 8px 0 8px 0;top: -20px;right:30px;z-index:9;font-size:12px;font-weight:bold;border:1px solid #808080;font-style:italic;}.twCodeMirrorParent .CodeMirror{height:auto}</style>\';
  if(!$("head #mainCSScodeMirror").length){
    $(\'head\').append(mainCSScodeMirror);
  }
  //all textareas ... that are present on page load
  $.each(codeMirrorTextareasIDorCLASS, function() {
    if($(this.selector).length){
      $(this.selector).parent().addClass("twCodeMirrorParent");
      var twCodeMirrorRealOrFly = "twCodeMirrorReal";
      var elemCodeMirror = $(this.selector);
      var ext = "test."+this.mode;
      var mode = autoDetectMode(ext).mode;
      var spec = autoDetectMode(ext).spec;
      var height = this.maxHeight;
      var wrap = this.wrap;
      if(this.name == "resource_content" && this.mode.indexOf("auto/") > -1){
        var mode = autoDetectMode("test."+splitMyJoint(this.mode,"/",1)).mode;
        var spec = autoDetectMode("test."+splitMyJoint(this.mode,"/",1)).spec;
        if(typeof twGetResourceContentType !== "undefined" && twGetResourceContentType !== ""){
          var mode = autoDetectMode(twGetResourceContentType).mode; //autoDetectMode value is supplied by serverside plugin
          var spec = autoDetectMode(twGetResourceContentType).spec;
        }
      }
      else if((this.name == "chunk" || this.name == "snippet" || this.name == "plugin" || this.name == "template") && this.mode.indexOf("auto/") > -1){
        var mode = splitMyJoint(this.mode,"/",1);
        if($("#modx-"+this.name+"-static-file").length && $("#modx-"+this.name+"-static-file").val() !== ""){
          var filePath = $("#modx-"+this.name+"-static-file").val();
          var mode = autoDetectMode(filePath).mode;
          var spec = autoDetectMode(filePath).spec;
        }
      }
      else if(this.name == "file" && this.mode.indexOf("auto/") > -1){
        var filePath = $("#modx-file-name").val();
        var mode = autoDetectMode(filePath).mode;
        var spec = autoDetectMode(filePath).spec;
      }
      $(this.selector).each(function(){ // for the sake of .modx-richtext and other classnames provided
        var elemCodeMirrorId = $(this).attr("id");
        modxCodeMirrorInit(elemCodeMirrorId,mode,spec,height,wrap,twCodeMirrorRealOrFly);
      })
    }
  });

  //all textareas added dynamically to the DOM ... quick create/update and anything else
  $(document).on("mouseenter", ".modx-window", function () {
    $this = $(this);
    $.each(codeMirrorFlyTextareasIDorCLASS, function() {
      if($this.find(this.selector).length){
        var thisTextarea = $this.find(this.selector);
        thisTextarea.parent().addClass("twCodeMirrorParent");
        twElemCodeMirrorId = thisTextarea.attr("id");
        twCodeMirrorMode = autoDetectMode(this.mode).mode;
        twCodeMirrorSpec = autoDetectMode(this.mode).spec;
        twCodeMirrorRealOrFly = "twCodeMirrorQuickFly";
        if($this.find("input[name=path]").length){ // for quick edit existing files
          twCodeMirrorMode = $this.find("input[name=path]").val()
          twCodeMirrorMode = autoDetectMode(twCodeMirrorMode).mode;
          twCodeMirrorSpec = autoDetectMode(twCodeMirrorMode).spec;
        }
        twCodeMirrorHeight = this.maxHeight;
        twCodeMirrorWrap = this.wrap;
        if(thisTextarea.is(\':visible\') && $this.find("#codeMirror_wrapper_"+twElemCodeMirrorId).length < 1 && !$this.find(".tinyCM").length){
        // modxCodeMirrorInit(twElemCodeMirrorId,twCodeMirrorMode,twCodeMirrorSpec,twCodeMirrorHeight,twCodeMirrorWrap,twCodeMirrorRealOrFly) // to fire this automatically, comment the bellow
          $this.find(".x-toolbar-left-row").append("<p onclick=changeMyButton(this,twElemCodeMirrorId,twCodeMirrorMode,twCodeMirrorSpec,twCodeMirrorHeight,twCodeMirrorWrap,twCodeMirrorRealOrFly); class=\\\'tinyCM codeMirrorActive x-btn x-btn-small x-btn-icon-small-left x-btn-noicon\\\' unselectable=\\\'on\\\'><em><button class=\\\'x-btn-text\\\'>Edit with CodeMirror</button></em></p>");
        }
      }
    });
  });
  if (typeof codeMirrorMoreSuperOptions == \'function\') { 
    codeMirrorMoreSuperOptions();
  }
}

/*#######################################################################################
     *********** Begin Kitchen Sink  (plcodeMirror in chunk or external file ***********
#######################################################################################*/
// font size / themes / height etc
function setCodeMirrorTheme(){
  var theme = this.text();
  var link = document.createElement(\'link\');
  link.onload = function(){
      window[getCodeMirrorCredential().thisCodeMirrorId].setOption("theme", theme);
  };
  link.rel = "stylesheet";
  link.media = "all";
  link.href = "[[+CodeMirrorSrc]]theme/"+theme+".css";
  document.getElementsByTagName(\'head\')[0].appendChild(link);
}
// kitchen sink
function setCodeMirrorSetFont(){
  getCodeMirrorCredential().thisActualCodeMirror.css("font-size",this.text());
  window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
}
// kitchen sink
function setCodeMirrorSetWrap(){
  if(this.text() == "free"){
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", "");
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
  else{
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize(+this.text()+290, "");
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
}
// kitchen sink
function setCodeMirrorSetHeight(){
  var thisTextarea = getCodeMirrorCredential().originalTextareaId;
  var option = this.text();
  if(option == "Textarea"){
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", $("#"+thisTextarea).height());
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
  else if(option == "1"){
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", 40);
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
  else if(option == "10"){
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", 200);
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
  else {
    window[getCodeMirrorCredential().thisCodeMirrorId].setSize("", "");
    window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
  }
}


// kitchen sink
// mode / syntax / language -- add as many as you like
codeMirrorMoreSuperOptionsToolButtonMODE =[
  {text: \'--- MOST COMMON ---\', value: \'\', disabled: true, selected: false, active: false},
  {text: \'HTML\', value: \'htmlmixed\'},
  {text: \'Markdown\', value: \'markdown\'},
  {text: \'CSS\', value: \'css\'},
  {text: \'PHP\', value: \'php\'},
  {text: \'JavaScript\', value: \'javascript\'},
  {text: \'JSON\', value: \'javascript\'},
  {text: \'Text\', value: \'null\'},
  {text: \'LESS\', value: \'css\'},
  {text: \'SASS\', value: \'sass\'},
  {text: \'SCSS\', value: \'css\'},
  {text: \'--- ALL SYNTAX ---\', value: \'\', disabled: true, selected: false, active: false},
  {text: \'APL\', value: \'apl\'},
  {text: \'PGP\', value: \'asciiarmor\'},
  {text: \'ASN.1\', value: \'asn.1\'},
  {text: \'Asterisk\', value: \'asterisk\'},
  {text: \'Brainfuck\', value: \'brainfuck\'},
  {text: \'C\', value: \'clike\'},
  {text: \'C++\', value: \'clike\'},
  {text: \'Cobol\', value: \'cobol\'},
  {text: \'C#\', value: \'clike\'},
  {text: \'Clojure\', value: \'clojure\'},
  {text: \'ClojureScript\', value: \'clojure\'},
  {text: \'Closure Stylesheets (GSS)\', value: \'css\'},
  {text: \'CMake\', value: \'cmake\'},
  {text: \'CoffeeScript\', value: \'coffeescript\'},
  {text: \'Common Lisp\', value: \'commonlisp\'},
  {text: \'Cypher\', value: \'cypher\'},
  {text: \'Cython\', value: \'python\'},
  {text: \'Crystal\', value: \'crystal\'},
  {text: \'CSS\', value: \'css\'},
  {text: \'CQL\', value: \'sql\'},
  {text: \'D\', value: \'d\'},
  {text: \'Dart\', value: \'dart\'},
  {text: \'diff\', value: \'diff\'},
  {text: \'Django\', value: \'django\'},
  {text: \'Dockerfile\', value: \'dockerfile\'},
  {text: \'DTD\', value: \'dtd\'},
  {text: \'Dylan\', value: \'dylan\'},
  {text: \'EBNF\', value: \'ebnf\'},
  {text: \'ECL\', value: \'ecl\'},
  {text: \'edn\', value: \'clojure\'},
  {text: \'Eiffel\', value: \'eiffel\'},
  {text: \'Elm\', value: \'elm\'},
  {text: \'Embedded Javascript\', value: \'htmlembedded\'},
  {text: \'Embedded Ruby\', value: \'htmlembedded\'},
  {text: \'Erlang\', value: \'erlang\'},
  {text: \'Factor\', value: \'factor\'},
  {text: \'FCL\', value: \'fcl\'},
  {text: \'Forth\', value: \'forth\'},
  {text: \'Fortran\', value: \'fortran\'},
  {text: \'F#\', value: \'mllike\'},
  {text: \'Gas\', value: \'gas\'},
  {text: \'Gherkin\', value: \'gherkin\'},
  {text: \'GitHub Flavored Markdown\', value: \'gfm\'},
  {text: \'Go\', value: \'go\'},
  {text: \'Groovy\', value: \'groovy\'},
  {text: \'HAML\', value: \'haml\'},
  {text: \'Haskell\', value: \'haskell\'},
  {text: \'Haskell (Literate)\', value: \'haskell-literate\'},
  {text: \'Haxe\', value: \'haxe\'},
  {text: \'HXML\', value: \'haxe\'},
  {text: \'ASP.NET\', value: \'htmlembedded\'},
  {text: \'HTML\', value: \'htmlmixed\'},
  {text: \'HTTP\', value: \'http\'},
  {text: \'IDL\', value: \'idl\'},
  {text: \'Jade\', value: \'jade\'},
  {text: \'Java\', value: \'clike\'},
  {text: \'Java Server Pages\', value: \'htmlembedded\'},
  {name: \'JavaScript\', value: \'javascript\'},
  {text: \'JSON\', value: \'javascript\'},
  {text: \'JSON-LD\', value: \'javascript\'},
  {text: \'JSX\', value: \'jsx\'},
  {text: \'Jinja2\', value: \'jinja2\'},
  {text: \'Julia\', value: \'julia\'},
  {text: \'Kotlin\', value: \'clike\'},
  {text: \'LESS\', value: \'css\'},
  {text: \'LiveScript\', value: \'livescript\'},
  {text: \'Lua\', value: \'lua\'},
  {text: \'Markdown\', value: \'markdown\'},
  {text: \'mIRC\', value: \'mirc\'},
  {text: \'MariaDB SQL\', value: \'sql\'},
  {text: \'Mathematica\', value: \'mathematica\'},
  {text: \'Modelica\', value: \'modelica\'},
  {text: \'MUMPS\', value: \'mumps\'},
  {text: \'MS SQL\', value: \'sql\'},
  {text: \'MySQL\', value: \'sql\'},
  {text: \'Nginx\', value: \'nginx\'},
  {text: \'NSIS\', value: \'nsis\'},
  {text: \'NTriples\', value: \'ntriples\'},
  {text: \'Objective C\', value: \'clike\'},
  {text: \'OCaml\', value: \'mllike\'},
  {text: \'Octave\', value: \'octave\'},
  {text: \'Oz\', value: \'oz\'},
  {text: \'Pascal\', value: \'pascal\'},
  {text: \'PEG.js\', value: \'pegjs\'},
  {text: \'Perl\', value: \'perl\'},
  {text: \'PHP\', value: \'php\'},
  {text: \'Pig\', value: \'pig\'},
  {text: \'Plain Text\', value: \'null\'},
  {text: \'PLSQL\', value: \'sql\'},
  {text: \'PowerShell\', value: \'powershell\'},
  {text: \'Properties files\', value: \'properties\'},
  {text: \'ProtoBuf\', value: \'protobuf\'},
  {text: \'Python\', value: \'python\'},
  {text: \'Puppet\', value: \'puppet\'},
  {text: \'Q\', value: \'q\'},
  {text: \'R\', value: \'r\'},
  {text: \'reStructuredText\', value: \'rst\'},
  {text: \'RPM Changes\', value: \'rpm\'},
  {text: \'RPM Spec\', value: \'rpm\'},
  {text: \'Ruby\', value: \'ruby\'},
  {text: \'Rust\', value: \'rust\'},
  {text: \'SAS\', value: \'sas\'},
  {text: \'Sass\', value: \'sass\'},
  {text: \'Scala\', value: \'clike\'},
  {text: \'Scheme\', value: \'scheme\'},
  {text: \'SCSS\', value: \'css\'},
  {text: \'Shell\', value: \'shell\'},
  {text: \'Sieve\', value: \'sieve\'},
  {text: \'Slim\', value: \'slim\'},
  {text: \'Smalltalk\', value: \'smalltalk\'},
  {text: \'Smarty\', value: \'smarty\'},
  {text: \'Solr\', value: \'solr\'},
  {text: \'Soy\', value: \'soy\'},
  {text: \'SPARQL\', value: \'sparql\'},
  {text: \'Spreadsheet\', value: \'spreadsheet\', alias: [\'excel\', \'formula\']},
  {text: \'SQL\', value: \'sql\'},
  {text: \'Squirrel\', value: \'clike\'},
  {text: \'Swift\', value: \'swift\'},
  {text: \'sTeX\', value: \'stex\'},
  {text: \'LaTeX\', value: \'stex\'},
  {text: \'SystemVerilog\', value: \'verilog\'},
  {text: \'Tcl\', value: \'tcl\'},
  {text: \'Textile\', value: \'textile\'},
  {text: \'TiddlyWiki \', value: \'tiddlywiki\'},
  {text: \'Tiki wiki\', value: \'tiki\'},
  {text: \'TOML\', value: \'toml\'},
  {text: \'Tornado\', value: \'tornado\'},
  {text: \'troff\', value: \'troff\'},
  {text: \'TTCN\', value: \'ttcn\'},
  {text: \'TTCN_CFG\', value: \'ttcn-cfg\'},
  {text: \'Turtle\', value: \'turtle\'},
  {text: \'TypeScript\', value: \'javascript\'},
  {text: \'Twig\', value: \'twig\'},
  {text: \'Web IDL\', value: \'webidl\'},
  {text: \'VB.NET\', value: \'vb\'},
  {text: \'VBScript\', value: \'vbscript\'},
  {text: \'Velocity\', value: \'velocity\'},
  {text: \'Verilog\', value: \'verilog\'},
  {text: \'VHDL\', value: \'vhdl\'},
  {text: \'XML\', value: \'xml\'},
  {text: \'XQuery\', value: \'xquery\'},
  {text: \'Yacas\', value: \'yacas\'},
  {text: \'YAML\', value: \'yaml\'},
  {text: \'Z80\', value: \'z80\'},
  {text: \'mscgen\', value: \'mscgen\'},
  {text: \'xu\', value: \'mscgen\'},
  {text: \'msgenny\', value: \'mscgen\'}
];

// kitchen sink
// change font size on the fly
codeMirrorMoreSuperOptionsToolButtonFONT =[
  {text: \'10px\', onclick: setCodeMirrorSetFont},
  {text: \'11px\', onclick: setCodeMirrorSetFont},
  {text: \'12px\', onclick: setCodeMirrorSetFont},
  {text: \'13px\', onclick: setCodeMirrorSetFont},
  {text: \'14px\', onclick: setCodeMirrorSetFont},
  {text: \'15px\', onclick: setCodeMirrorSetFont},
  {text: \'16px\', onclick: setCodeMirrorSetFont},
  {text: \'18px\', onclick: setCodeMirrorSetFont},
  {text: \'20px\', onclick: setCodeMirrorSetFont},
  {text: \'24px\', onclick: setCodeMirrorSetFont}
];

// kitchen sink
// change wrap style
codeMirrorMoreSuperOptionsToolButtonWRAP =[
  {text: \'free\', onclick: setCodeMirrorSetWrap},
  {text: \'40\', onclick: setCodeMirrorSetWrap},
  {text: \'180\', onclick: setCodeMirrorSetWrap}
];

// kitchen sink
// change height/max lines
codeMirrorMoreSuperOptionsToolButtonHEIGHT =[
  {text: \'1\', onclick: setCodeMirrorSetHeight},
  {text: \'10\', onclick: setCodeMirrorSetHeight},
  {text: \'Infinity\', onclick: setCodeMirrorSetHeight},
  {text: \'Textarea\', onclick: setCodeMirrorSetHeight}
];

// kitchen sink
// change theme on the fly
codeMirrorMoreSuperOptionsToolButtonTHEME = [
  {text: \'3024-day\', onclick: setCodeMirrorTheme},
  {text: \'3024-night\', onclick: setCodeMirrorTheme},
  {text: \'abcdef\', onclick: setCodeMirrorTheme},
  {text: \'ambiance\', onclick: setCodeMirrorTheme},
  {text: \'base16-dark\', onclick: setCodeMirrorTheme},
  {text: \'base16-light\', onclick: setCodeMirrorTheme},
  {text: \'bespin\', onclick: setCodeMirrorTheme},
  {text: \'blackboard\', onclick: setCodeMirrorTheme},
  {text: \'cobalt\', onclick: setCodeMirrorTheme},
  {text: \'colorforth\', onclick: setCodeMirrorTheme},
  {text: \'dracula\', onclick: setCodeMirrorTheme},
  {text: \'eclipse\', onclick: setCodeMirrorTheme},
  {text: \'elegant\', onclick: setCodeMirrorTheme},
  {text: \'erlang-dark\', onclick: setCodeMirrorTheme},
  {text: \'hopscotch\', onclick: setCodeMirrorTheme},
  {text: \'icecoder\', onclick: setCodeMirrorTheme},
  {text: \'isotope\', onclick: setCodeMirrorTheme},
  {text: \'lesser-dark\', onclick: setCodeMirrorTheme},
  {text: \'liquibyte\', onclick: setCodeMirrorTheme},
  {text: \'material\', onclick: setCodeMirrorTheme},
  {text: \'mbo\', onclick: setCodeMirrorTheme},
  {text: \'mdn-like\', onclick: setCodeMirrorTheme},
  {text: \'midnight\', onclick: setCodeMirrorTheme},
  {text: \'monokai\', onclick: setCodeMirrorTheme},
  {text: \'neat\', onclick: setCodeMirrorTheme},
  {text: \'neo\', onclick: setCodeMirrorTheme},
  {text: \'night\', onclick: setCodeMirrorTheme},
  {text: \'paraiso-dark\', onclick: setCodeMirrorTheme},
  {text: \'paraiso-light\', onclick: setCodeMirrorTheme},
  {text: \'pastel-on-dark\', onclick: setCodeMirrorTheme},
  {text: \'railscasts\', onclick: setCodeMirrorTheme},
  {text: \'rubyblue\', onclick: setCodeMirrorTheme},
  {text: \'seti\', onclick: setCodeMirrorTheme},
  {text: \'solarized dark\', onclick: setCodeMirrorTheme},
  {text: \'solarized light\', onclick: setCodeMirrorTheme},
  {text: \'the-matrix\', onclick: setCodeMirrorTheme},
  {text: \'tomorrow-night-bright\', onclick: setCodeMirrorTheme},
  {text: \'tomorrow-night-eighties\', onclick: setCodeMirrorTheme},
  {text: \'ttcn\', onclick: setCodeMirrorTheme},
  {text: \'twilight\', onclick: setCodeMirrorTheme},
  {text: \'vibrant-ink\', onclick: setCodeMirrorTheme},
  {text: \'xq-dark\', onclick: setCodeMirrorTheme},
  {text: \'xq-light\', onclick: setCodeMirrorTheme},
  {text: \'yeti\', onclick: setCodeMirrorTheme},
  {text: \'zenburn\', onclick: setCodeMirrorTheme}
];

// kitchen sink toolbar ini
function codeMirrorMoreSuperOptions(){
  if(typeof tinymce !== "undefined" && !$("#codeMirrorMoreSuperOptions_wrapper").length){
    $("body").append("<div id=codeMirrorMoreSuperOptions_wrapper><div id=codeMirrorMoreSuperOptions_wrapper_boss></div><div style=display:none; id=codeMirrorMoreSuperOptions_wrapper_sub></div></div>");
    tinymce.ui.Factory.create({
      type: "button",
      icon: "fullpage",
      classes:"moreCodeMirrorAttr",
      tooltip: "CodeMirror settings...",
      onPostRender:function(){
        $(".mce-moreCodeMirrorAttr").attr("class","mce-moreCodeMirrorAttr");
        $(".mce-moreCodeMirrorAttr button").contents().unwrap();
      },
      onclick: function(){
        if($(\'#codeMirrorMoreSuperOptions_wrapper_sub:hidden\').length){
          $(\'#codeMirrorMoreSuperOptions_wrapper_sub\').show()
          $(\'.mce-moreCodeMirrorAttr\').addClass(\'mce-active\')
        }
        else{
          $(\'#codeMirrorMoreSuperOptions_wrapper_sub\').hide()
          $(\'.mce-moreCodeMirrorAttr\').removeClass(\'mce-active\')
        }
      }
    }).renderTo(document.getElementById("codeMirrorMoreSuperOptions_wrapper_boss"));
    tinymce.ui.Factory.create({
      type: \'button\',
      icon: \'fullscreen\',
      classes: \'codeMirrorMoreSuperOptionsFS\',
      tooltip: \'Fullscreen\',
      onclick: function(){
        if(getCodeMirrorCredential().thisWrap.hasClass("codeMirror_editor_fullScreen")){
          getCodeMirrorCredential().thisWrap.removeClass("codeMirror_editor_fullScreen");
          $("#codeMirrorMoreSuperOptions_wrapper").removeClass("codeMirror_editor_fullScreen");
          window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
        }
        else{
          getCodeMirrorCredential().thisWrap.addClass("codeMirror_editor_fullScreen")
          $("#codeMirrorMoreSuperOptions_wrapper").addClass("codeMirror_editor_fullScreen");
          window[getCodeMirrorCredential().thisCodeMirrorId].refresh()
        }
      },
      onPostRender:function(){
        // $(".mce-codeMirrorMoreSuperOptions .mce-caret").remove();
        $(document).on("mouseenter click", ".codeMirror_wrapper_modx", function () {
          if(!$(this).find("#codeMirrorMoreSuperOptions_wrapper").length){
            $("#codeMirrorMoreSuperOptions_wrapper").prependTo($(this)).fadeIn();
            codeMirrorMoreSuperOptions();
          }
        })
      }
    }).renderTo(document.getElementById("codeMirrorMoreSuperOptions_wrapper_sub"));
    tinymce.ui.Factory.create({
      type: \'listbox\',
      icon: \'code\',
      classes: \'codeMirrorMoreSuperOptionsMODE\',
      tooltip: \'Syntax / Mode\',
      menu: codeMirrorMoreSuperOptionsToolButtonMODE,
      onPostRender: function(){
        $(".mce-codeMirrorMoreSuperOptionsMODE .mce-caret").remove();
        $(".mce-codeMirrorMoreSuperOptionsMODE .mce-txt").remove();
      },
      onselect: function(){
        window[getCodeMirrorCredential().thisCodeMirrorId].setOption("mode", this.value())
        CodeMirror.autoLoadMode(window[getCodeMirrorCredential().thisCodeMirrorId], this.value());
        window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
        getCodeMirrorCredential().thisSink.text(this.value())
        $(".mce-codeMirrorMoreSuperOptionsMODE .mce-txt").remove();
      }
    }).renderTo(document.getElementById("codeMirrorMoreSuperOptions_wrapper_sub"));

    tinymce.ui.Factory.create({
      type: \'menubutton\',
      classes: \'codeMirrorMoreSuperOptionsMORE\',
      tooltip: \'Themes and more Options\',
      onPostRender:function(){
        // $(".mce-codeMirrorMoreSuperOptionsMORE .mce-caret").remove();
      },
      menu: [
      // {
      //   text: "Show Invisibles",
      //   classes: "setShowInvisibles",
      //   onclick: function(){
      //     if(window[getCodeMirrorCredential().thisCodeMirrorId].getOption("showInvisibles")){
      //       window[getCodeMirrorCredential().thisCodeMirrorId].setShowInvisibles(false);
      //     }
      //     else{
      //       window[getCodeMirrorCredential().thisCodeMirrorId].setShowInvisibles(true);
      //     }
      //   }
      // },
      {
        text: "Refresh Editor",
        onclick: function(){
          window[getCodeMirrorCredential().thisCodeMirrorId].refresh();
        }
      },
      {
        text: "Soft Wrap",
        menu: codeMirrorMoreSuperOptionsToolButtonWRAP
      },
      {
        text: "Max Height",
        menu: codeMirrorMoreSuperOptionsToolButtonHEIGHT
      },
      {
        text: "Theme",
        menu: codeMirrorMoreSuperOptionsToolButtonTHEME
      },
      {
        text: "Font Size",
        menu: codeMirrorMoreSuperOptionsToolButtonFONT,
      },
      ],
    }).renderTo(document.getElementById("codeMirrorMoreSuperOptions_wrapper_sub"));
    if(tinymce.editors.length < 1){
      // fileManagerTopNavModalSkin = MODx.config.assets_url+"components/tinymcewrapper/tinymceskins/modxPericles/"+"skin.min.css";
      tinymce.DOM.loadCSS(fileManagerTopNavModalSkin+"/skin.min.css");
    }
  }
}
// ************************** END Kitchen Sink **************************


/*#######################################################################################
   *************** MODX INIT : load applicable JS and CSS files ***************
#######################################################################################*/
/*-------------------
more jargon underneath
load or not load what files you want to use
-----------------------------------------------------------*/


//load applicable CSS files
function loadcss(filename){
  var fileref=document.createElement("link")
  fileref.setAttribute("rel", "stylesheet")
  fileref.setAttribute("type", "text/css")
  fileref.setAttribute("href", filename)
  if (typeof fileref!="undefined"){
    document.getElementsByTagName("head")[0].appendChild(fileref);
   }
}

function loadThemCSS(){
  loadcss(\'[[+CodeMirrorSrc]]codemirror.min.css\');
  loadcss(\'[[+CodeMirrorSrc]]addon/dialog/dialog.css\');
  loadcss(\'[[+CodeMirrorSrc]]addon/search/matchesonscrollbar.css\');
  loadcss(\'[[+CodeMirrorSrc]]addon/display/fullscreen.css\');
  loadcss(\'[[+CodeMirrorSrc]]addon/fold/foldgutter.css\');
  [[+CodeMirrorTHEME:notempty=`loadcss(\'[[+CodeMirrorSrc]]theme/[[+CodeMirrorTHEME]].css\');`]]
}
loadThemCSS();
CodeMirror.modeURL = "[[+CodeMirrorSrc]]mode/%N/%N.js";

//load applicable JS files
Ext.Loader.load([
  \'[[++assets_url]]components/tinymcewrapper/tinymceplugins/codemirror/emmet.js\',
  \'[[+CodeMirrorSrc]]addon/wrap/hardwrap.min.js\',
  \'[[+CodeMirrorSrc]]addon/selection/active-line.min.js\',
  \'[[+CodeMirrorSrc]]addon/search/searchcursor.min.js\',
  \'[[+CodeMirrorSrc]]addon/scroll/annotatescrollbar.js\',
  \'[[+CodeMirrorSrc]]addon/search/matchesonscrollbar.js\',
  \'[[+CodeMirrorSrc]]addon/search/jump-to-line.min.js\',
  \'[[+CodeMirrorSrc]]addon/search/search.min.js\',
  \'[[+CodeMirrorSrc]]addon/dialog/dialog.min.js\',
  \'[[+CodeMirrorSrc]]addon/hint/show-hint.min.js\',
  \'[[+CodeMirrorSrc]]addon/hint/html-hint.min.js\',
  \'[[+CodeMirrorSrc]]addon/hint/xml-hint.min.js\',
  \'[[+CodeMirrorSrc]]addon/edit/closetag.min.js\',
  \'[[+CodeMirrorSrc]]addon/edit/trailingspace.min.js\',
  \'[[+CodeMirrorSrc]]addon/display/fullscreen.js\',
  \'[[+CodeMirrorSrc]]addon/fold/foldcode.js\',
  \'[[+CodeMirrorSrc]]addon/fold/foldgutter.js\',
  \'[[+CodeMirrorSrc]]addon/fold/brace-fold.js\',
  \'[[+CodeMirrorSrc]]addon/fold/xml-fold.js\',
  \'[[+CodeMirrorSrc]]addon/fold/comment-fold.js\',
  \'[[+CodeMirrorSrc]]addon/edit/matchbrackets.min.js\',
  \'[[+CodeMirrorSrc]]addon/lint/lint.js\',
  \'[[+CodeMirrorSrc]]addon/lint/css-lint.js\',
  \'[[+CodeMirrorSrc]]addon/lint/json-lint.js\',
  \'[[+CodeMirrorSrc]]addon/lint/javascript-lint.js\',
  \'[[+CodeMirrorSrc]]addon/lint/css-lint.js\',
  \'[[+CodeMirrorSrc]]addon/mode/loadmode.js\',
  \'[[+CodeMirrorSrc]]mode/meta.js\'
 ],
  function() {
   Ext.onReady(function () {
    codeMirrorTextareas();
   // })
   },this,{delay:50})
  },
  this,
  \'preserveOrder\'
);

emmetForCodeMirrorIsLoaded = true; // this helps other CodeMirror implementations to piggy-back on this emmet
',
    ),
  ),
  'efe0e5172bdb6df717fd3188ad4bd14e' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperAce',
    ),
    'object' => 
    array (
      'id' => 29,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperAce',
      'description' => 'A simple chunk to teach you how to totally dominate the usage of Ace according to all the possible specs provided on the official website.',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => '/*
#######################################################
TinymceWrapperAce is a chunk to let any whoever dominate the initialization of the powerful Ace code editor.
This tiny config can work any where and with anything in MODX.
It will sync with TinyMCE if TinyMCE RTE is detected

Yours truly, donshakespeare @ MODX forums
#######################################################

##Language / Mode / Syntax
"mode": "html", -- always html 
"mode": "auto/html",
  -- (elements) static source extension dependent - fallback to html
  -- (resources) content type (mimetype) dependent - fallsback to html

##max-height of editor in lines
"maxHeightLines": integer / "textarea" / "Infinity",
  -- integer - e.g 10 means editor will be 10 lines in height
  -- "textarea" - editor will borrow the fixed height of its original MODX textarea and will auto overflow
  -- "Infiinity" - editor will keep growing according to the height of its content.
*/


aceTextareasIDorCLASS = [
  {
    "name": "introtext",
    "selector": "#modx-resource-introtext",
    "mode": "html",
    "wrap": "free",
    "maxHeightLines": "textarea",
  },
  {
    "name": "description",
    "selector": "#modx-resource-description",
    "mode": "html",
    "wrap": "free",
    "maxHeightLines": "textarea"
  },
  {
    "name": "richtext",
    "selector": ".modx-richtext",
    "mode": "html",
    "wrap": "free",
    "maxHeightLines": 8,
  },
  {
    "name": "resource_content",
    "selector": "#ta",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "chunk",
    "selector": "#modx-chunk-snippet",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "template",
    "selector": "#modx-template-content",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "snippet",
    "selector": "#modx-snippet-snippet",
    "mode": "auto/php",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "plugin",
    "selector": "#modx-plugin-plugincode",
    "mode": "auto/php",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "file",
    "selector": "#modx-file-content",
    "mode": "auto/css",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  }
];

// all on-the-fly textareas attached to mouseenter event (e.g Quick update/create textareas)
aceFlyTextareasIDorCLASS = [
  {
    "selector": "textarea[name=snippet]",
    "mode": "php",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  }, {
    "selector": "textarea[name=content]",
    "mode": "html",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  }, {
    "selector": "textarea[name=plugincode]",
    "mode": "php",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  }
];

//extend if you like
modxAceMimeTypes = {
  \'text/html\': \'html\',
  \'text/plain\': \'text\',
  \'text/css\': \'css\',
  \'text/javascript\': \'javascript\',
  \'application/javascript\': \'javascript\',
  \'text/x-less\': \'less\',
  \'application/xml\': \'xml\',
  \'text/xml\': \'xml\',
  \'application/json\': \'json\',
  \'text/x-markdown\': \'markdown\'
};


// actual Ace Init configuration (get all from official Ace site, oh yea - total freedom!!!)
aceInnerInit = function(mode, theme, maxLines, wrap) {
  var inner = {
    maxLines: maxLines ? maxLines : "",
    minLines: 3,
    [[+AceTHEME:notempty=`theme: "ace/theme/[[+AceTHEME]]",`]]
    mode: mode,
    wrap: wrap,
    showPrintMargin: false,
    // showInvisibles :true,
    // enableEmmet: false,
    displayIndentGuides :true,
    fontSize: 15,
    tabSize: 2,
    useWorker: false
  }
  return inner;
};

/*#######################################################################################
************************** abandon all fear - all ye who enter **************************
#######################################################################################*/

modxAceInit = function (elem, mode, maxLines, wrap, realFly){
  var originalTextarea = document.getElementById(elem);
  var maxLinesAce = maxLines;
  originalTextarea.style.display  = "none";
  if(maxLinesAce == "textarea"){
    var height = $(originalTextarea).height();
    var maxLinesAce = "";
  }
  var aceTempDiv =
    $(\'<div>\', {
      \'id\': \'ace_wrapper_\'+originalTextarea.id,
      \'class\': \'ace_wrapper_modx \'+realFly,
      \'data-ace\': \'ace_id_\'+originalTextarea.id,
      \'data-origin\': originalTextarea.id,
      \'data-mode\': mode,
      \'data-maxL\': maxLines,
      \'data-wrap\': wrap
    }).prepend(
      $(\'<span>\', {
        \'class\': \'aceKitchenSink\',
        \'text\': mode,
        \'title\': \'maxLine: \'+maxLines+\'... wrap: \'+wrap
      }),
      $(\'<div>\', {
        \'id\': \'ace_id_\'+originalTextarea.id,
        // \'class\': originalTextarea.className,
        \'width\': "auto",
        \'height\': height ? height : "",
        \'data-origin\': originalTextarea.id
      })
    ).hide().insertBefore(originalTextarea);
  // var aceTempDiv =
  //     $(\'<div>\', {
  //       \'id\': \'ace_id_\'+originalTextarea.id,
  //       \'class\': originalTextarea.className,
  //       \'width\': "auto",
  //       \'height\': height ? height : "",
  //       \'data-origin\': originalTextarea.id
  //     }).insertBefore(originalTextarea);
  // var aceEditor = ace.edit(aceTempDiv[0]);

  var aceEditor = ace.edit(\'ace_id_\'+originalTextarea.id);
  aceEditor.$blockScrolling = Infinity;
  aceEditor.setOptions(aceInnerInit("ace/mode/"+mode, "", maxLinesAce, wrap));
  aceEditor.getSession().setValue(originalTextarea.value);
  aceEditor.setAutoScrollEditorIntoView(true);
  $(\'#ace_wrapper_\'+originalTextarea.id).delay(100).fadeIn();
  setTimeout(function(){
    aceEditor.resize();
  },200)
  aceSetContentsilentMODX = false;
  aceEditor.on("focus", function() {
    if(typeof tinymce !== "undefined" && tinymce.get(elem)){
      tinymce.get(elem).on("keyup change", function() {
        if($("#ace_id_"+originalTextarea.id).length){
          aceSetContentsilentMODX = true;
          aceEditor.setValue(tinymce.get(elem).getContent());
          aceSetContentsilentMODX = false;
        }
      })
    }
  })
  aceEditor.on("change", function() {
    if(!aceEditor.getOption("useWorker")){
      aceEditor.getSession().setUseWorker(true);
    }
    if (aceSetContentsilentMODX){
      return;
    }
    else{
      originalTextarea.value = aceEditor.getValue();
      if(typeof tinymce !== "undefined" && tinymce.get(elem)){
        tinymce.get(elem).setContent(aceEditor.getValue());
      }
    }
  })
  $(originalTextarea).parents(".modx-tv").find(".modx-tv-reset").on("click", function(){
    setTimeout(function(){
      aceEditor.getSession().setValue(originalTextarea.value);
      aceEditor.resize();
    },200)
  });
  $(originalTextarea).on("keyup change", function() {
    aceSetContentsilentMODX = true;
    aceEditor.setValue($(this).val());
    aceSetContentsilentMODX = false;
   })

  if($(\'#ace_wrapper_\'+originalTextarea.id).hasClass("twAceQuickFly")){
    aceEditor.commands.addCommand({
      name: \'saveFile\',
      bindKey: {
        win: \'Ctrl-S\',
        mac: \'Command-S\',
        sender: \'editor|cli\'
      },
      exec: function(env, args, request) {
        $(\'#ace_wrapper_\'+originalTextarea.id).parents(\'.modx-window\').find(\'button:contains("Save")\').first().trigger("click");
      }
    });
  }
}
function changeMyButton(thisButton,id,mode,maxLines,wrap,realFly){
  if($(thisButton).hasClass("aceActive")){
    if($("#"+id).is(\':visible\') ){
      $(thisButton).find("button").text("Remove Ace");
      $(thisButton).removeClass("aceActive");
      modxAceInit(id,mode,maxLines,wrap,realFly)
    }
  }
  else{
    $(thisButton).addClass("aceActive");
    $(thisButton).find("button").text("Edit With Ace");
    ace.edit("ace_id_"+id).destroy()
    $("#ace_wrapper_"+id).remove();
    $("#"+id).fadeIn();
  }
}

function splitMyJoint(val,joint,index) {
  var item = val.split(joint);
  return item[index];
}

function getAceCredential() {
  var thisWrap = $("#aceMoreSuperOptions_wrapper").parent();
  var thisSink = thisWrap.find(".aceKitchenSink");
  var originalTextareaId = thisWrap.data("origin");
  var thisAceId = thisWrap.data("ace");
  return {
      thisWrap: thisWrap,
      thisSink: thisSink,
      originalTextareaId: originalTextareaId,
      thisAceId: thisAceId,
    }
}

function autoDetectMode(filePath){
  var modelist = ace.require("ace/ext/modelist");
  var mode = modelist.getModeForPath(filePath).mode;
  return splitMyJoint(mode,"/",2); //auto defaults to text mode
}

function grabMeAnExtension(val) {
  if (modxAceMimeTypes.hasOwnProperty(val)) {
    return(modxAceMimeTypes[val]);
    return false;
  }
}

function aceTextareas(){
  var mainCSSace = \'<style id=mainCSSace>.x-window-body,.x-tab-panel{overflow-x:hidden!important;}#aceMoreSuperOptions_wrapper{position:absolute!important;z-index:9!important;right:10px;top:-20px;}#aceMoreSuperOptions_wrapper .mce-listbox button{padding-right:10px!important;}#aceMoreSuperOptions_wrapper .mce-btn-has-text .mce-ico{padding-right:0!important;}#aceMoreSuperOptions_wrapper.ace_editor_fullScreen{position:fixed!important;right:23px;}#aceMoreSuperOptions_wrapper_boss{float:right;cursor:pointer;}#aceMoreSuperOptions_wrapper_boss button{padding:0;}#aceMoreSuperOptions_wrapper_sub {margin-top:24px;}.ace_wrapper_modx.ace_editor_fullScreen{position:fixed;top:0;width:100%;height:100%;left:0;right:0;bottom:0;z-index:26;background:#fff;overflow: auto;}.aceKitchenSink{position:absolute;background: #fff;color: #000;padding: 0px 6px;border-radius: 8px 0 8px 0;top: -20px;right:30px;z-index:9;font-size:12px;font-weight:bold;border:1px solid #808080;font-style:italic;}</style>\';
  if(!$("head #mainCSSace").length){
    $(\'head\').append(mainCSSace);
  }
  //all textareas ... that are present on page load
  $.each(aceTextareasIDorCLASS, function() {
    if($(this.selector).length){
      $(this.selector).parent().addClass("twAceParent");
      var twAceRealOrFly = "twAceReal";
      var elemAce = $(this.selector);
      var mode = this.mode;
      var height = this.maxHeightLines;
      var wrap = this.wrap;
      if(this.name == "resource_content" && this.mode.indexOf("auto/") > -1){
        var mode = splitMyJoint(this.mode,"/",1);
        if(typeof twGetResourceContentType !== "undefined" && twGetResourceContentType !== ""){
          var mode = grabMeAnExtension(twGetResourceContentType); //twGetResourceContentType value is supplied by serverside plugin
        }
      }
      else if((this.name == "chunk" || this.name == "snippet" || this.name == "plugin" || this.name == "template") && this.mode.indexOf("auto/") > -1){
        var mode = splitMyJoint(this.mode,"/",1);
        if($("#modx-"+this.name+"-static-file").length && $("#modx-"+this.name+"-static-file").val() !== ""){
          var filePath = $("#modx-"+this.name+"-static-file").val();
          var mode = autoDetectMode(filePath);
        }
      }
      else if(this.name == "file" && this.mode.indexOf("auto/") > -1){
        var filePath = $("#modx-file-name").val();
        var mode = autoDetectMode(filePath);
      }
      $(this.selector).each(function(){ // for the sake of .modx-richtext and other classnames provided
        var elemAceId = $(this).attr("id");
        modxAceInit(elemAceId,mode,height,wrap,twAceRealOrFly);
      })
    }
  });

  //all textareas added dynamically to the DOM ... quick create/update and anything else
  $(document).on("mouseenter", ".modx-window", function () {
    $this = $(this);
    $.each(aceFlyTextareasIDorCLASS, function() {
      if($this.find(this.selector).length){
        var thisTextarea = $this.find(this.selector);
        thisTextarea.parent().addClass("twAceParent");
        twElemAceId = thisTextarea.attr("id");
        twAceMode = this.mode;
        twAceRealOrFly = "twAceQuickFly";
        if($this.find("input[name=path]").length){ // for quick edit existing files
          twAceMode = $this.find("input[name=path]").val()
          twAceMode = autoDetectMode(twAceMode);
        }
        twAceHeight = this.maxHeightLines;
        twAceWrap = this.wrap;
        if(thisTextarea.is(\':visible\') && $this.find("#ace_wrapper_"+twElemAceId).length < 1 && !$this.find(".tinyACE").length){
        // modxAceInit(twElemAceId,twAceMode,twAceHeight,twAceWrap,twAceRealOrFly) // to fire this automatically, comment the bellow
          $this.find(".x-toolbar-left-row").append("<p onclick=changeMyButton(this,twElemAceId,twAceMode,twAceHeight,twAceWrap,twAceRealOrFly); class=\\\'tinyACE aceActive x-btn x-btn-small x-btn-icon-small-left x-btn-noicon\\\' unselectable=\\\'on\\\'><em><button class=\\\'x-btn-text\\\'>Edit with Ace</button></em></p>");
        }
      }
    });
  });
  if (typeof aceMoreSuperOptions == \'function\') { 
    aceMoreSuperOptions();
  }
}

/*#######################################################################################
     *********** Begin Kitchen Sink  (place in chunk or external file ***********
#######################################################################################*/
// font size / themes / height etc
function setAceSetTheme(){
  var val = this.text().replace(/\\s+/g, \'_\').toLowerCase();
  ace.edit(getAceCredential().thisAceId).setTheme("ace/theme/"+val);
}
// kitchen sink
function setAceSetFont(){
  ace.edit(getAceCredential().thisAceId).setFontSize(this.text());
  ace.edit(getAceCredential().thisAceId).resize();
}
// kitchen sink
function setAceSetWrap(){
  ace.edit(getAceCredential().thisAceId).setOption("wrap", this.text());
  ace.edit(getAceCredential().thisAceId).resize();
}
// kitchen sink
function setAceSetHeight(){
  var thisTextarea = getAceCredential().originalTextareaId;
  var option = this.text();
  if(option == "Textarea"){
    ace.edit(getAceCredential().thisAceId).setOption("maxLines", false);
    $("#"+getAceCredential().thisAceId).height( $("#"+thisTextarea).height());
    ace.edit(getAceCredential().thisAceId).resize();
  }
  else {
    ace.edit(getAceCredential().thisAceId).setOption("maxLines", this.text());
    ace.edit(getAceCredential().thisAceId).resize();
  }
}


// kitchen sink
// mode / syntax / language -- add as many as you like
aceMoreSuperOptionsToolButtonMODE =[
  {text: \'--- MOST COMMON ---\', value: \'\', disabled: true, selected: false, active: false},
  {text: \'HTML\', value: \'html\'},
  {text: \'Markdown\', value: \'markdown\'},
  {text: \'CSS\', value: \'css\'},
  {text: \'PHP\', value: \'php\'},
  {text: \'JavaScript\', value: \'javascript\'},
  {text: \'JSON\', value: \'json\'},
  {text: \'Text\', value: \'text\'},
  {text: \'LESS\', value: \'less\'},
  {text: \'SASS\', value: \'sass\'},
  {text: \'SCSS\', value: \'scss\'},
  {text: \'Smarty\', value: \'smarty\'},
  {text: \'SVG\', value: \'svg\'},
  {text: \'Twig\', value: \'twig\'},
  {text: \'XML\', value: \'xml\'},
  {text: \'--- ALL SYNTAX ---\', value: \'\', disabled: true, selected: false, active: false},
  {text: \'ABAP\', value:\'abap\'},
  {text: \'ABC\', value:\'abc\'},
  {text: \'ActionScript\', value:\'actionscript\'},
  {text: \'ADA\', value:\'ada\'},
  {text: \'Apache\', value:\'apache_conf\'},
  {text: \'AsciiDoc\', value:\'asciidoc\'},
  {text: \'Assembly\', value:\'assembly_x86\'},
  {text: \'AutoHotKey\', value:\'autohotkey\'},
  {text: \'BatchFile\', value:\'batchfile\'},
  {text: \'C\', value:\'c_cpp\'},
  {text: \'C9Search\', value:\'c9search\'},
  {text: \'Cirru\', value:\'cirru\'},
  {text: \'Clojure\', value:\'clojure\'},
  {text: \'Cobol\', value:\'cobol\'},
  {text: \'CoffeeScript\', value:\'coffee\'},
  {text: \'ColdFusion\', value:\'coldfusion\'},
  {text: \'C\', value:\'csharp\'},
  {text: \'CSS\', value:\'css\'},
  {text: \'Curly\', value:\'curly\'},
  {text: \'D\', value:\'d\'},
  {text: \'Dart\', value:\'dart\'},
  {text: \'Diff\', value:\'diff\'},
  {text: \'Dockerfile\', value:\'dockerfile\'},
  {text: \'Dot\', value:\'dot\'},
  {text: \'Eiffel\', value:\'eiffel\'},
  {text: \'EJS\', value:\'ejs\'},
  {text: \'Elixir\', value:\'elixir\'},
  {text: \'Elm\', value:\'elm\'},
  {text: \'Erlang\', value:\'erlang\'},
  {text: \'Forth\', value:\'forth\'},
  {text: \'FreeMarker\', value:\'ftl\'},
  {text: \'Gcode\', value:\'gcode\'},
  {text: \'Gherkin\', value:\'gherkin\'},
  {text: \'Gitignore\', value:\'gitignore\'},
  {text: \'Glsl\', value:\'glsl\'},
  {text: \'Gobstones\', value:\'gobstones\'},
  {text: \'Go\', value:\'golang\'},
  {text: \'Groovy\', value:\'groovy\'},
  {text: \'HAML\', value:\'haml\'},
  {text: \'Handlebars\', value:\'handlebars\'},
  {text: \'Haskell\', value:\'haskell\'},
  {text: \'haXe\', value:\'haxe\'},
  {text: \'HTML\', value:\'html\'},
  {text: \'HTML\', value:\'html_elixir\'},
  {text: \'HTML\', value:\'html_ruby\'},
  {text: \'INI\', value:\'ini\'},
  {text: \'Io\', value:\'io\'},
  {text: \'Jack\', value:\'jack\'},
  {text: \'Jade\', value:\'jade\'},
  {text: \'Java\', value:\'java\'},
  {text: \'JavaScript\', value:\'javascript\'},
  {text: \'JSON\', value:\'json\'},
  {text: \'JSONiq\', value:\'jsoniq\'},
  {text: \'JSP\', value:\'jsp\'},
  {text: \'JSX\', value:\'jsx\'},
  {text: \'Julia\', value:\'julia\'},
  {text: \'LaTeX\', value:\'latex\'},
  {text: \'Lean\', value:\'lean\'},
  {text: \'LESS\', value:\'less\'},
  {text: \'Liquid\', value:\'liquid\'},
  {text: \'Lisp\', value:\'lisp\'},
  {text: \'LiveScript\', value:\'livescript\'},
  {text: \'LogiQL\', value:\'logiql\'},
  {text: \'LSL\', value:\'lsl\'},
  {text: \'Lua\', value:\'lua\'},
  {text: \'LuaPage\', value:\'luapage\'},
  {text: \'Lucene\', value:\'lucene\'},
  {text: \'Makefile\', value:\'makefile\'},
  {text: \'Markdown\', value:\'markdown\'},
  {text: \'Mask\', value:\'mask\'},
  {text: \'MATLAB\', value:\'matlab\'},
  {text: \'Maze\', value:\'maze\'},
  {text: \'MEL\', value:\'mel\'},
  {text: \'MUSHCode\', value:\'mushcode\'},
  {text: \'MySQL\', value:\'mysql\'},
  {text: \'Nix\', value:\'nix\'},
  {text: \'NSIS\', value:\'nsis\'},
  {text: \'Objective\', value:\'objectivec\'},
  {text: \'OCaml\', value:\'ocaml\'},
  {text: \'Pascal\', value:\'pascal\'},
  {text: \'Perl\', value:\'perl\'},
  {text: \'pgSQL\', value:\'pgsql\'},
  {text: \'PHP\', value:\'php\'},
  {text: \'Powershell\', value:\'powershell\'},
  {text: \'Praat\', value:\'praat\'},
  {text: \'Prolog\', value:\'prolog\'},
  {text: \'Properties\', value:\'properties\'},
  {text: \'Protobuf\', value:\'protobuf\'},
  {text: \'Python\', value:\'python\'},
  {text: \'R\', value:\'r\'},
  {text: \'Razor\', value:\'razor\'},
  {text: \'RDoc\', value:\'rdoc\'},
  {text: \'RHTML\', value:\'rhtml\'},
  {text: \'RST\', value:\'rst\'},
  {text: \'Ruby\', value:\'ruby\'},
  {text: \'Rust\', value:\'rust\'},
  {text: \'SASS\', value:\'sass\'},
  {text: \'SCAD\', value:\'scad\'},
  {text: \'Scala\', value:\'scala\'},
  {text: \'Scheme\', value:\'scheme\'},
  {text: \'SCSS\', value:\'scss\'},
  {text: \'SH\', value:\'sh\'},
  {text: \'SJS\', value:\'sjs\'},
  {text: \'Smarty\', value:\'smarty\'},
  {text: \'snippets\', value:\'snippets\'},
  {text: \'Soy\', value:\'soy_template\'},
  {text: \'Space\', value:\'space\'},
  {text: \'SQL\', value:\'sql\'},
  {text: \'SQLServer\', value:\'sqlserver\'},
  {text: \'Stylus\', value:\'stylus\'},
  {text: \'SVG\', value:\'svg\'},
  {text: \'Swift\', value:\'swift\'},
  {text: \'Tcl\', value:\'tcl\'},
  {text: \'Tex\', value:\'tex\'},
  {text: \'Text\', value:\'text\'},
  {text: \'Textile\', value:\'textile\'},
  {text: \'Toml\', value:\'toml\'},
  {text: \'Twig\', value:\'twig\'},
  {text: \'Typescript\', value:\'typescript\'},
  {text: \'Vala\', value:\'vala\'},
  {text: \'VBScript\', value:\'vbscript\'},
  {text: \'Velocity\', value:\'velocity\'},
  {text: \'Verilog\', value:\'verilog\'},
  {text: \'VHDL\', value:\'vhdl\'},
  {text: \'Wollok\', value:\'wollok\'},
  {text: \'XML\', value:\'xml\'},
  {text: \'XQuery\', value:\'xquery\'},
  {text: \'YAML\', value:\'yaml\'},
  {text: \'Django\', value:\'django\'}
];

// kitchen sink
// change font size on the fly
aceMoreSuperOptionsToolButtonFONT =[
  {text: \'10px\', onclick: setAceSetFont},
  {text: \'11px\', onclick: setAceSetFont},
  {text: \'12px\', onclick: setAceSetFont},
  {text: \'13px\', onclick: setAceSetFont},
  {text: \'14px\', onclick: setAceSetFont},
  {text: \'15px\', onclick: setAceSetFont},
  {text: \'16px\', onclick: setAceSetFont},
  {text: \'18px\', onclick: setAceSetFont},
  {text: \'20px\', onclick: setAceSetFont},
  {text: \'24px\', onclick: setAceSetFont}
];

// kitchen sink
// change wrap style
aceMoreSuperOptionsToolButtonWRAP =[
  {text: \'free\', onclick: setAceSetWrap},
  {text: \'40\', onclick: setAceSetWrap},
  {text: \'80\', onclick: setAceSetWrap},
  {text: \'off\', onclick: setAceSetWrap},
];

// kitchen sink
// change height/max lines
aceMoreSuperOptionsToolButtonHEIGHT =[
  {text: \'1\', onclick: setAceSetHeight},
  {text: \'10\', onclick: setAceSetHeight},
  {text: \'Infinity\', onclick: setAceSetHeight},
  {text: \'Textarea\', onclick: setAceSetHeight}
];

// kitchen sink
// change theme on the fly
aceMoreSuperOptionsToolButtonTHEME = [
  {text: \'---- Bright ----\', disabled: true, selected: false},
  {text: \'Chrome\', onclick: setAceSetTheme},
  {text: \'Clouds\', onclick: setAceSetTheme},
  {text: \'Crimson Editor\', onclick: setAceSetTheme},
  {text: \'Dawn\', onclick: setAceSetTheme},
  {text: \'Dreamweaver\', onclick: setAceSetTheme},
  {text: \'Eclipse\', onclick: setAceSetTheme},
  {text: \'GitHub\', onclick: setAceSetTheme},
  {text: \'IPlastic\', onclick: setAceSetTheme},
  {text: \'Solarized Light\', onclick: setAceSetTheme},
  {text: \'TextMate\', onclick: setAceSetTheme},
  {text: \'Tomorrow\', onclick: setAceSetTheme},
  {text: \'XCode\', onclick: setAceSetTheme},
  {text: \'Kuroir\', onclick: setAceSetTheme},
  {text: \'KatzenMilch\', onclick: setAceSetTheme},
  {text: \'SQL Server\', onclick: setAceSetTheme},
  {text: \'---- Dark ----\', disabled: true, selected: false},
  {text: \'Ambiance\', onclick: setAceSetTheme},
  {text: \'Chaos\', onclick: setAceSetTheme},
  {text: \'Clouds Midnight\', onclick: setAceSetTheme},
  {text: \'Cobalt\', onclick: setAceSetTheme},
  {text: \'idle Fingers\', onclick: setAceSetTheme},
  {text: \'krTheme\', onclick: setAceSetTheme},
  {text: \'Merbivore\', onclick: setAceSetTheme},
  {text: \'Merbivore Soft\', onclick: setAceSetTheme},
  {text: \'Mono Industrial\', onclick: setAceSetTheme},
  {text: \'Monokai\', onclick: setAceSetTheme},
  {text: \'Pastel on dark\', onclick: setAceSetTheme},
  {text: \'Solarized Dark\', onclick: setAceSetTheme},
  {text: \'Terminal\', onclick: setAceSetTheme},
  {text: \'Tomorrow Night\', onclick: setAceSetTheme},
  {text: \'Tomorrow Night Blue\', onclick: setAceSetTheme},
  {text: \'Tomorrow Night Bright\', onclick: setAceSetTheme},
  {text: \'Tomorrow Night Eighties\', onclick: setAceSetTheme},
  {text: \'Twilight\', onclick: setAceSetTheme},
  {text: \'Vibrant Ink\', onclick: setAceSetTheme}
];

// kitchen sink toolbar ini
function aceMoreSuperOptions(){
  if(typeof tinymce !== "undefined" && !$("#aceMoreSuperOptions_wrapper").length){
    $("body").append("<div id=aceMoreSuperOptions_wrapper><div id=aceMoreSuperOptions_wrapper_boss></div><div style=display:none; id=aceMoreSuperOptions_wrapper_sub></div></div>");
    tinymce.ui.Factory.create({
      type: "button",
      icon: "fullpage",
      classes:"moreAceAttr",
      tooltip: "Ace Settings...",
      onPostRender:function(){
        $(".mce-moreAceAttr").attr("class","mce-moreAceAttr");
        $(".mce-moreAceAttr button").contents().unwrap();
      },
      onclick: function(){
        if($(\'#aceMoreSuperOptions_wrapper_sub:hidden\').length){
          $(\'#aceMoreSuperOptions_wrapper_sub\').show()
          $(\'.mce-moreAceAttr\').addClass(\'mce-active\')
        }
        else{
          $(\'#aceMoreSuperOptions_wrapper_sub\').hide()
          $(\'.mce-moreAceAttr\').removeClass(\'mce-active\')
        }
      }
    }).renderTo(document.getElementById("aceMoreSuperOptions_wrapper_boss"));
    tinymce.ui.Factory.create({
      type: \'button\',
      icon: \'fullscreen\',
      classes: \'aceMoreSuperOptionsFS\',
      tooltip: \'Fullscreen\',
      onclick: function(){
        if(getAceCredential().thisWrap.hasClass("ace_editor_fullScreen")){
          getAceCredential().thisWrap.removeClass("ace_editor_fullScreen");
          $("#aceMoreSuperOptions_wrapper").removeClass("ace_editor_fullScreen");
          ace.edit(getAceCredential().thisAceId).resize();
        }
        else{
          getAceCredential().thisWrap.addClass("ace_editor_fullScreen")
          $("#aceMoreSuperOptions_wrapper").addClass("ace_editor_fullScreen");
          ace.edit(getAceCredential().thisAceId).resize();
        }
      },
      onPostRender:function(){
        // $(".mce-aceMoreSuperOptions .mce-caret").remove();
        $(document).on("mouseenter click", ".ace_wrapper_modx", function () {
          if(!$(this).find("#aceMoreSuperOptions_wrapper").length){
            $("#aceMoreSuperOptions_wrapper").prependTo($(this)).fadeIn();
            aceMoreSuperOptions();
          }
        })
      }
    }).renderTo(document.getElementById("aceMoreSuperOptions_wrapper_sub"));
    tinymce.ui.Factory.create({
      type: \'listbox\',
      icon: \'code\',
      classes: \'aceMoreSuperOptionsMODE\',
      tooltip: \'Syntax / Mode\',
      menu: aceMoreSuperOptionsToolButtonMODE,
      onPostRender: function(){
        $(".mce-aceMoreSuperOptionsMODE .mce-caret").remove();
        $(".mce-aceMoreSuperOptionsMODE .mce-txt").remove();
      },
      onselect: function(){
        ace.edit(getAceCredential().thisAceId).getSession().setMode("ace/mode/"+this.value())
        ace.edit(getAceCredential().thisAceId).resize();
        getAceCredential().thisSink.text(this.value())
        $(".mce-aceMoreSuperOptionsMODE .mce-txt").remove();
      }
    }).renderTo(document.getElementById("aceMoreSuperOptions_wrapper_sub"));

    tinymce.ui.Factory.create({
      // icon: "fullpage",
      type: \'menubutton\',
      classes: \'aceMoreSuperOptionsMORE\',
      tooltip: \'Themes and more Options\',
      onPostRender:function(){
        // $(".mce-aceMoreSuperOptionsMORE .mce-caret").remove();
      },
      menu: [
      {
        text: "Show Invisibles",
        classes: "setShowInvisibles",
        onclick: function(){
          if(ace.edit(getAceCredential().thisAceId).getOption("showInvisibles")){
            ace.edit(getAceCredential().thisAceId).setShowInvisibles(false);
          }
          else{
            ace.edit(getAceCredential().thisAceId).setShowInvisibles(true);
          }
        }
      },
      {
        text: "Scroll Past End",
        classes: "setScroll",
        onclick: function(){
          if(ace.edit(getAceCredential().thisAceId).getOption("scrollPastEnd")){
            ace.edit(getAceCredential().thisAceId).setOption("scrollPastEnd", false);
            ace.edit(getAceCredential().thisAceId).resize();
          }
          else{
            ace.edit(getAceCredential().thisAceId).setOption("scrollPastEnd", true);
            ace.edit(getAceCredential().thisAceId).resize();
          }
        }
      },
      {
        text: "Refresh Editor",
        onclick: function(){
          ace.edit(getAceCredential().thisAceId).resize();
        }
      },
      {
        text: "Soft Wrap",
        menu: aceMoreSuperOptionsToolButtonWRAP
      },
      {
        text: "Max Height",
        menu: aceMoreSuperOptionsToolButtonHEIGHT
      },
      {
        text: "Theme",
        menu: aceMoreSuperOptionsToolButtonTHEME
      },
      {
        text: "Font Size",
        menu: aceMoreSuperOptionsToolButtonFONT,
      },
      ],
    }).renderTo(document.getElementById("aceMoreSuperOptions_wrapper_sub"));
    if(tinymce.editors.length < 1){
      // fileManagerTopNavModalSkin = MODx.config.assets_url+"components/tinymcewrapper/tinymceskins/modxPericles/"+"skin.min.css";
      tinymce.DOM.loadCSS(fileManagerTopNavModalSkin+"/skin.min.css");
    }
  }
}
// ************************** END Kitchen Sink **************************


/*#######################################################################################
   *************** MODX INIT : load applicable JS and CSS files ***************
#######################################################################################*/
Ext.Loader.load([
  \'[[++assets_url]]components/tinymcewrapper/tinymceplugins/ace/emmet.min.js\',
  \'[[+AceSrc]]ext-emmet.js\',
  \'[[+AceSrc]]ext-modelist.js\',
 ],
  function() {
    Ext.onReady(function () {
      aceTextareas();
    // }) // use this instead of delay
    },this,{delay:50}) // delay 100 always works well
  }
)
emmetForAceIsLoaded = true; // this helps other Ace implementations to piggy-back on this emmet
',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => '/*
#######################################################
TinymceWrapperAce is a chunk to let any whoever dominate the initialization of the powerful Ace code editor.
This tiny config can work any where and with anything in MODX.
It will sync with TinyMCE if TinyMCE RTE is detected

Yours truly, donshakespeare @ MODX forums
#######################################################

##Language / Mode / Syntax
"mode": "html", -- always html 
"mode": "auto/html",
  -- (elements) static source extension dependent - fallback to html
  -- (resources) content type (mimetype) dependent - fallsback to html

##max-height of editor in lines
"maxHeightLines": integer / "textarea" / "Infinity",
  -- integer - e.g 10 means editor will be 10 lines in height
  -- "textarea" - editor will borrow the fixed height of its original MODX textarea and will auto overflow
  -- "Infiinity" - editor will keep growing according to the height of its content.
*/


aceTextareasIDorCLASS = [
  {
    "name": "introtext",
    "selector": "#modx-resource-introtext",
    "mode": "html",
    "wrap": "free",
    "maxHeightLines": "textarea",
  },
  {
    "name": "description",
    "selector": "#modx-resource-description",
    "mode": "html",
    "wrap": "free",
    "maxHeightLines": "textarea"
  },
  {
    "name": "richtext",
    "selector": ".modx-richtext",
    "mode": "html",
    "wrap": "free",
    "maxHeightLines": 8,
  },
  {
    "name": "resource_content",
    "selector": "#ta",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "chunk",
    "selector": "#modx-chunk-snippet",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "template",
    "selector": "#modx-template-content",
    "mode": "auto/html",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "snippet",
    "selector": "#modx-snippet-snippet",
    "mode": "auto/php",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "plugin",
    "selector": "#modx-plugin-plugincode",
    "mode": "auto/php",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  },
  {
    "name": "file",
    "selector": "#modx-file-content",
    "mode": "auto/css",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  }
];

// all on-the-fly textareas attached to mouseenter event (e.g Quick update/create textareas)
aceFlyTextareasIDorCLASS = [
  {
    "selector": "textarea[name=snippet]",
    "mode": "php",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  }, {
    "selector": "textarea[name=content]",
    "mode": "html",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  }, {
    "selector": "textarea[name=plugincode]",
    "mode": "php",
    "wrap": "free",
    "maxHeightLines": "Infinity",
  }
];

//extend if you like
modxAceMimeTypes = {
  \'text/html\': \'html\',
  \'text/plain\': \'text\',
  \'text/css\': \'css\',
  \'text/javascript\': \'javascript\',
  \'application/javascript\': \'javascript\',
  \'text/x-less\': \'less\',
  \'application/xml\': \'xml\',
  \'text/xml\': \'xml\',
  \'application/json\': \'json\',
  \'text/x-markdown\': \'markdown\'
};


// actual Ace Init configuration (get all from official Ace site, oh yea - total freedom!!!)
aceInnerInit = function(mode, theme, maxLines, wrap) {
  var inner = {
    maxLines: maxLines ? maxLines : "",
    minLines: 3,
    [[+AceTHEME:notempty=`theme: "ace/theme/[[+AceTHEME]]",`]]
    mode: mode,
    wrap: wrap,
    showPrintMargin: false,
    // showInvisibles :true,
    // enableEmmet: false,
    displayIndentGuides :true,
    fontSize: 15,
    tabSize: 2,
    useWorker: false
  }
  return inner;
};

/*#######################################################################################
************************** abandon all fear - all ye who enter **************************
#######################################################################################*/

modxAceInit = function (elem, mode, maxLines, wrap, realFly){
  var originalTextarea = document.getElementById(elem);
  var maxLinesAce = maxLines;
  originalTextarea.style.display  = "none";
  if(maxLinesAce == "textarea"){
    var height = $(originalTextarea).height();
    var maxLinesAce = "";
  }
  var aceTempDiv =
    $(\'<div>\', {
      \'id\': \'ace_wrapper_\'+originalTextarea.id,
      \'class\': \'ace_wrapper_modx \'+realFly,
      \'data-ace\': \'ace_id_\'+originalTextarea.id,
      \'data-origin\': originalTextarea.id,
      \'data-mode\': mode,
      \'data-maxL\': maxLines,
      \'data-wrap\': wrap
    }).prepend(
      $(\'<span>\', {
        \'class\': \'aceKitchenSink\',
        \'text\': mode,
        \'title\': \'maxLine: \'+maxLines+\'... wrap: \'+wrap
      }),
      $(\'<div>\', {
        \'id\': \'ace_id_\'+originalTextarea.id,
        // \'class\': originalTextarea.className,
        \'width\': "auto",
        \'height\': height ? height : "",
        \'data-origin\': originalTextarea.id
      })
    ).hide().insertBefore(originalTextarea);
  // var aceTempDiv =
  //     $(\'<div>\', {
  //       \'id\': \'ace_id_\'+originalTextarea.id,
  //       \'class\': originalTextarea.className,
  //       \'width\': "auto",
  //       \'height\': height ? height : "",
  //       \'data-origin\': originalTextarea.id
  //     }).insertBefore(originalTextarea);
  // var aceEditor = ace.edit(aceTempDiv[0]);

  var aceEditor = ace.edit(\'ace_id_\'+originalTextarea.id);
  aceEditor.$blockScrolling = Infinity;
  aceEditor.setOptions(aceInnerInit("ace/mode/"+mode, "", maxLinesAce, wrap));
  aceEditor.getSession().setValue(originalTextarea.value);
  aceEditor.setAutoScrollEditorIntoView(true);
  $(\'#ace_wrapper_\'+originalTextarea.id).delay(100).fadeIn();
  setTimeout(function(){
    aceEditor.resize();
  },200)
  aceSetContentsilentMODX = false;
  aceEditor.on("focus", function() {
    if(typeof tinymce !== "undefined" && tinymce.get(elem)){
      tinymce.get(elem).on("keyup change", function() {
        if($("#ace_id_"+originalTextarea.id).length){
          aceSetContentsilentMODX = true;
          aceEditor.setValue(tinymce.get(elem).getContent());
          aceSetContentsilentMODX = false;
        }
      })
    }
  })
  aceEditor.on("change", function() {
    if(!aceEditor.getOption("useWorker")){
      aceEditor.getSession().setUseWorker(true);
    }
    if (aceSetContentsilentMODX){
      return;
    }
    else{
      originalTextarea.value = aceEditor.getValue();
      if(typeof tinymce !== "undefined" && tinymce.get(elem)){
        tinymce.get(elem).setContent(aceEditor.getValue());
      }
    }
  })
  $(originalTextarea).parents(".modx-tv").find(".modx-tv-reset").on("click", function(){
    setTimeout(function(){
      aceEditor.getSession().setValue(originalTextarea.value);
      aceEditor.resize();
    },200)
  });
  $(originalTextarea).on("keyup change", function() {
    aceSetContentsilentMODX = true;
    aceEditor.setValue($(this).val());
    aceSetContentsilentMODX = false;
   })

  if($(\'#ace_wrapper_\'+originalTextarea.id).hasClass("twAceQuickFly")){
    aceEditor.commands.addCommand({
      name: \'saveFile\',
      bindKey: {
        win: \'Ctrl-S\',
        mac: \'Command-S\',
        sender: \'editor|cli\'
      },
      exec: function(env, args, request) {
        $(\'#ace_wrapper_\'+originalTextarea.id).parents(\'.modx-window\').find(\'button:contains("Save")\').first().trigger("click");
      }
    });
  }
}
function changeMyButton(thisButton,id,mode,maxLines,wrap,realFly){
  if($(thisButton).hasClass("aceActive")){
    if($("#"+id).is(\':visible\') ){
      $(thisButton).find("button").text("Remove Ace");
      $(thisButton).removeClass("aceActive");
      modxAceInit(id,mode,maxLines,wrap,realFly)
    }
  }
  else{
    $(thisButton).addClass("aceActive");
    $(thisButton).find("button").text("Edit With Ace");
    ace.edit("ace_id_"+id).destroy()
    $("#ace_wrapper_"+id).remove();
    $("#"+id).fadeIn();
  }
}

function splitMyJoint(val,joint,index) {
  var item = val.split(joint);
  return item[index];
}

function getAceCredential() {
  var thisWrap = $("#aceMoreSuperOptions_wrapper").parent();
  var thisSink = thisWrap.find(".aceKitchenSink");
  var originalTextareaId = thisWrap.data("origin");
  var thisAceId = thisWrap.data("ace");
  return {
      thisWrap: thisWrap,
      thisSink: thisSink,
      originalTextareaId: originalTextareaId,
      thisAceId: thisAceId,
    }
}

function autoDetectMode(filePath){
  var modelist = ace.require("ace/ext/modelist");
  var mode = modelist.getModeForPath(filePath).mode;
  return splitMyJoint(mode,"/",2); //auto defaults to text mode
}

function grabMeAnExtension(val) {
  if (modxAceMimeTypes.hasOwnProperty(val)) {
    return(modxAceMimeTypes[val]);
    return false;
  }
}

function aceTextareas(){
  var mainCSSace = \'<style id=mainCSSace>.x-window-body,.x-tab-panel{overflow-x:hidden!important;}#aceMoreSuperOptions_wrapper{position:absolute!important;z-index:9!important;right:10px;top:-20px;}#aceMoreSuperOptions_wrapper .mce-listbox button{padding-right:10px!important;}#aceMoreSuperOptions_wrapper .mce-btn-has-text .mce-ico{padding-right:0!important;}#aceMoreSuperOptions_wrapper.ace_editor_fullScreen{position:fixed!important;right:23px;}#aceMoreSuperOptions_wrapper_boss{float:right;cursor:pointer;}#aceMoreSuperOptions_wrapper_boss button{padding:0;}#aceMoreSuperOptions_wrapper_sub {margin-top:24px;}.ace_wrapper_modx.ace_editor_fullScreen{position:fixed;top:0;width:100%;height:100%;left:0;right:0;bottom:0;z-index:26;background:#fff;overflow: auto;}.aceKitchenSink{position:absolute;background: #fff;color: #000;padding: 0px 6px;border-radius: 8px 0 8px 0;top: -20px;right:30px;z-index:9;font-size:12px;font-weight:bold;border:1px solid #808080;font-style:italic;}</style>\';
  if(!$("head #mainCSSace").length){
    $(\'head\').append(mainCSSace);
  }
  //all textareas ... that are present on page load
  $.each(aceTextareasIDorCLASS, function() {
    if($(this.selector).length){
      $(this.selector).parent().addClass("twAceParent");
      var twAceRealOrFly = "twAceReal";
      var elemAce = $(this.selector);
      var mode = this.mode;
      var height = this.maxHeightLines;
      var wrap = this.wrap;
      if(this.name == "resource_content" && this.mode.indexOf("auto/") > -1){
        var mode = splitMyJoint(this.mode,"/",1);
        if(typeof twGetResourceContentType !== "undefined" && twGetResourceContentType !== ""){
          var mode = grabMeAnExtension(twGetResourceContentType); //twGetResourceContentType value is supplied by serverside plugin
        }
      }
      else if((this.name == "chunk" || this.name == "snippet" || this.name == "plugin" || this.name == "template") && this.mode.indexOf("auto/") > -1){
        var mode = splitMyJoint(this.mode,"/",1);
        if($("#modx-"+this.name+"-static-file").length && $("#modx-"+this.name+"-static-file").val() !== ""){
          var filePath = $("#modx-"+this.name+"-static-file").val();
          var mode = autoDetectMode(filePath);
        }
      }
      else if(this.name == "file" && this.mode.indexOf("auto/") > -1){
        var filePath = $("#modx-file-name").val();
        var mode = autoDetectMode(filePath);
      }
      $(this.selector).each(function(){ // for the sake of .modx-richtext and other classnames provided
        var elemAceId = $(this).attr("id");
        modxAceInit(elemAceId,mode,height,wrap,twAceRealOrFly);
      })
    }
  });

  //all textareas added dynamically to the DOM ... quick create/update and anything else
  $(document).on("mouseenter", ".modx-window", function () {
    $this = $(this);
    $.each(aceFlyTextareasIDorCLASS, function() {
      if($this.find(this.selector).length){
        var thisTextarea = $this.find(this.selector);
        thisTextarea.parent().addClass("twAceParent");
        twElemAceId = thisTextarea.attr("id");
        twAceMode = this.mode;
        twAceRealOrFly = "twAceQuickFly";
        if($this.find("input[name=path]").length){ // for quick edit existing files
          twAceMode = $this.find("input[name=path]").val()
          twAceMode = autoDetectMode(twAceMode);
        }
        twAceHeight = this.maxHeightLines;
        twAceWrap = this.wrap;
        if(thisTextarea.is(\':visible\') && $this.find("#ace_wrapper_"+twElemAceId).length < 1 && !$this.find(".tinyACE").length){
        // modxAceInit(twElemAceId,twAceMode,twAceHeight,twAceWrap,twAceRealOrFly) // to fire this automatically, comment the bellow
          $this.find(".x-toolbar-left-row").append("<p onclick=changeMyButton(this,twElemAceId,twAceMode,twAceHeight,twAceWrap,twAceRealOrFly); class=\\\'tinyACE aceActive x-btn x-btn-small x-btn-icon-small-left x-btn-noicon\\\' unselectable=\\\'on\\\'><em><button class=\\\'x-btn-text\\\'>Edit with Ace</button></em></p>");
        }
      }
    });
  });
  if (typeof aceMoreSuperOptions == \'function\') { 
    aceMoreSuperOptions();
  }
}

/*#######################################################################################
     *********** Begin Kitchen Sink  (place in chunk or external file ***********
#######################################################################################*/
// font size / themes / height etc
function setAceSetTheme(){
  var val = this.text().replace(/\\s+/g, \'_\').toLowerCase();
  ace.edit(getAceCredential().thisAceId).setTheme("ace/theme/"+val);
}
// kitchen sink
function setAceSetFont(){
  ace.edit(getAceCredential().thisAceId).setFontSize(this.text());
  ace.edit(getAceCredential().thisAceId).resize();
}
// kitchen sink
function setAceSetWrap(){
  ace.edit(getAceCredential().thisAceId).setOption("wrap", this.text());
  ace.edit(getAceCredential().thisAceId).resize();
}
// kitchen sink
function setAceSetHeight(){
  var thisTextarea = getAceCredential().originalTextareaId;
  var option = this.text();
  if(option == "Textarea"){
    ace.edit(getAceCredential().thisAceId).setOption("maxLines", false);
    $("#"+getAceCredential().thisAceId).height( $("#"+thisTextarea).height());
    ace.edit(getAceCredential().thisAceId).resize();
  }
  else {
    ace.edit(getAceCredential().thisAceId).setOption("maxLines", this.text());
    ace.edit(getAceCredential().thisAceId).resize();
  }
}


// kitchen sink
// mode / syntax / language -- add as many as you like
aceMoreSuperOptionsToolButtonMODE =[
  {text: \'--- MOST COMMON ---\', value: \'\', disabled: true, selected: false, active: false},
  {text: \'HTML\', value: \'html\'},
  {text: \'Markdown\', value: \'markdown\'},
  {text: \'CSS\', value: \'css\'},
  {text: \'PHP\', value: \'php\'},
  {text: \'JavaScript\', value: \'javascript\'},
  {text: \'JSON\', value: \'json\'},
  {text: \'Text\', value: \'text\'},
  {text: \'LESS\', value: \'less\'},
  {text: \'SASS\', value: \'sass\'},
  {text: \'SCSS\', value: \'scss\'},
  {text: \'Smarty\', value: \'smarty\'},
  {text: \'SVG\', value: \'svg\'},
  {text: \'Twig\', value: \'twig\'},
  {text: \'XML\', value: \'xml\'},
  {text: \'--- ALL SYNTAX ---\', value: \'\', disabled: true, selected: false, active: false},
  {text: \'ABAP\', value:\'abap\'},
  {text: \'ABC\', value:\'abc\'},
  {text: \'ActionScript\', value:\'actionscript\'},
  {text: \'ADA\', value:\'ada\'},
  {text: \'Apache\', value:\'apache_conf\'},
  {text: \'AsciiDoc\', value:\'asciidoc\'},
  {text: \'Assembly\', value:\'assembly_x86\'},
  {text: \'AutoHotKey\', value:\'autohotkey\'},
  {text: \'BatchFile\', value:\'batchfile\'},
  {text: \'C\', value:\'c_cpp\'},
  {text: \'C9Search\', value:\'c9search\'},
  {text: \'Cirru\', value:\'cirru\'},
  {text: \'Clojure\', value:\'clojure\'},
  {text: \'Cobol\', value:\'cobol\'},
  {text: \'CoffeeScript\', value:\'coffee\'},
  {text: \'ColdFusion\', value:\'coldfusion\'},
  {text: \'C\', value:\'csharp\'},
  {text: \'CSS\', value:\'css\'},
  {text: \'Curly\', value:\'curly\'},
  {text: \'D\', value:\'d\'},
  {text: \'Dart\', value:\'dart\'},
  {text: \'Diff\', value:\'diff\'},
  {text: \'Dockerfile\', value:\'dockerfile\'},
  {text: \'Dot\', value:\'dot\'},
  {text: \'Eiffel\', value:\'eiffel\'},
  {text: \'EJS\', value:\'ejs\'},
  {text: \'Elixir\', value:\'elixir\'},
  {text: \'Elm\', value:\'elm\'},
  {text: \'Erlang\', value:\'erlang\'},
  {text: \'Forth\', value:\'forth\'},
  {text: \'FreeMarker\', value:\'ftl\'},
  {text: \'Gcode\', value:\'gcode\'},
  {text: \'Gherkin\', value:\'gherkin\'},
  {text: \'Gitignore\', value:\'gitignore\'},
  {text: \'Glsl\', value:\'glsl\'},
  {text: \'Gobstones\', value:\'gobstones\'},
  {text: \'Go\', value:\'golang\'},
  {text: \'Groovy\', value:\'groovy\'},
  {text: \'HAML\', value:\'haml\'},
  {text: \'Handlebars\', value:\'handlebars\'},
  {text: \'Haskell\', value:\'haskell\'},
  {text: \'haXe\', value:\'haxe\'},
  {text: \'HTML\', value:\'html\'},
  {text: \'HTML\', value:\'html_elixir\'},
  {text: \'HTML\', value:\'html_ruby\'},
  {text: \'INI\', value:\'ini\'},
  {text: \'Io\', value:\'io\'},
  {text: \'Jack\', value:\'jack\'},
  {text: \'Jade\', value:\'jade\'},
  {text: \'Java\', value:\'java\'},
  {text: \'JavaScript\', value:\'javascript\'},
  {text: \'JSON\', value:\'json\'},
  {text: \'JSONiq\', value:\'jsoniq\'},
  {text: \'JSP\', value:\'jsp\'},
  {text: \'JSX\', value:\'jsx\'},
  {text: \'Julia\', value:\'julia\'},
  {text: \'LaTeX\', value:\'latex\'},
  {text: \'Lean\', value:\'lean\'},
  {text: \'LESS\', value:\'less\'},
  {text: \'Liquid\', value:\'liquid\'},
  {text: \'Lisp\', value:\'lisp\'},
  {text: \'LiveScript\', value:\'livescript\'},
  {text: \'LogiQL\', value:\'logiql\'},
  {text: \'LSL\', value:\'lsl\'},
  {text: \'Lua\', value:\'lua\'},
  {text: \'LuaPage\', value:\'luapage\'},
  {text: \'Lucene\', value:\'lucene\'},
  {text: \'Makefile\', value:\'makefile\'},
  {text: \'Markdown\', value:\'markdown\'},
  {text: \'Mask\', value:\'mask\'},
  {text: \'MATLAB\', value:\'matlab\'},
  {text: \'Maze\', value:\'maze\'},
  {text: \'MEL\', value:\'mel\'},
  {text: \'MUSHCode\', value:\'mushcode\'},
  {text: \'MySQL\', value:\'mysql\'},
  {text: \'Nix\', value:\'nix\'},
  {text: \'NSIS\', value:\'nsis\'},
  {text: \'Objective\', value:\'objectivec\'},
  {text: \'OCaml\', value:\'ocaml\'},
  {text: \'Pascal\', value:\'pascal\'},
  {text: \'Perl\', value:\'perl\'},
  {text: \'pgSQL\', value:\'pgsql\'},
  {text: \'PHP\', value:\'php\'},
  {text: \'Powershell\', value:\'powershell\'},
  {text: \'Praat\', value:\'praat\'},
  {text: \'Prolog\', value:\'prolog\'},
  {text: \'Properties\', value:\'properties\'},
  {text: \'Protobuf\', value:\'protobuf\'},
  {text: \'Python\', value:\'python\'},
  {text: \'R\', value:\'r\'},
  {text: \'Razor\', value:\'razor\'},
  {text: \'RDoc\', value:\'rdoc\'},
  {text: \'RHTML\', value:\'rhtml\'},
  {text: \'RST\', value:\'rst\'},
  {text: \'Ruby\', value:\'ruby\'},
  {text: \'Rust\', value:\'rust\'},
  {text: \'SASS\', value:\'sass\'},
  {text: \'SCAD\', value:\'scad\'},
  {text: \'Scala\', value:\'scala\'},
  {text: \'Scheme\', value:\'scheme\'},
  {text: \'SCSS\', value:\'scss\'},
  {text: \'SH\', value:\'sh\'},
  {text: \'SJS\', value:\'sjs\'},
  {text: \'Smarty\', value:\'smarty\'},
  {text: \'snippets\', value:\'snippets\'},
  {text: \'Soy\', value:\'soy_template\'},
  {text: \'Space\', value:\'space\'},
  {text: \'SQL\', value:\'sql\'},
  {text: \'SQLServer\', value:\'sqlserver\'},
  {text: \'Stylus\', value:\'stylus\'},
  {text: \'SVG\', value:\'svg\'},
  {text: \'Swift\', value:\'swift\'},
  {text: \'Tcl\', value:\'tcl\'},
  {text: \'Tex\', value:\'tex\'},
  {text: \'Text\', value:\'text\'},
  {text: \'Textile\', value:\'textile\'},
  {text: \'Toml\', value:\'toml\'},
  {text: \'Twig\', value:\'twig\'},
  {text: \'Typescript\', value:\'typescript\'},
  {text: \'Vala\', value:\'vala\'},
  {text: \'VBScript\', value:\'vbscript\'},
  {text: \'Velocity\', value:\'velocity\'},
  {text: \'Verilog\', value:\'verilog\'},
  {text: \'VHDL\', value:\'vhdl\'},
  {text: \'Wollok\', value:\'wollok\'},
  {text: \'XML\', value:\'xml\'},
  {text: \'XQuery\', value:\'xquery\'},
  {text: \'YAML\', value:\'yaml\'},
  {text: \'Django\', value:\'django\'}
];

// kitchen sink
// change font size on the fly
aceMoreSuperOptionsToolButtonFONT =[
  {text: \'10px\', onclick: setAceSetFont},
  {text: \'11px\', onclick: setAceSetFont},
  {text: \'12px\', onclick: setAceSetFont},
  {text: \'13px\', onclick: setAceSetFont},
  {text: \'14px\', onclick: setAceSetFont},
  {text: \'15px\', onclick: setAceSetFont},
  {text: \'16px\', onclick: setAceSetFont},
  {text: \'18px\', onclick: setAceSetFont},
  {text: \'20px\', onclick: setAceSetFont},
  {text: \'24px\', onclick: setAceSetFont}
];

// kitchen sink
// change wrap style
aceMoreSuperOptionsToolButtonWRAP =[
  {text: \'free\', onclick: setAceSetWrap},
  {text: \'40\', onclick: setAceSetWrap},
  {text: \'80\', onclick: setAceSetWrap},
  {text: \'off\', onclick: setAceSetWrap},
];

// kitchen sink
// change height/max lines
aceMoreSuperOptionsToolButtonHEIGHT =[
  {text: \'1\', onclick: setAceSetHeight},
  {text: \'10\', onclick: setAceSetHeight},
  {text: \'Infinity\', onclick: setAceSetHeight},
  {text: \'Textarea\', onclick: setAceSetHeight}
];

// kitchen sink
// change theme on the fly
aceMoreSuperOptionsToolButtonTHEME = [
  {text: \'---- Bright ----\', disabled: true, selected: false},
  {text: \'Chrome\', onclick: setAceSetTheme},
  {text: \'Clouds\', onclick: setAceSetTheme},
  {text: \'Crimson Editor\', onclick: setAceSetTheme},
  {text: \'Dawn\', onclick: setAceSetTheme},
  {text: \'Dreamweaver\', onclick: setAceSetTheme},
  {text: \'Eclipse\', onclick: setAceSetTheme},
  {text: \'GitHub\', onclick: setAceSetTheme},
  {text: \'IPlastic\', onclick: setAceSetTheme},
  {text: \'Solarized Light\', onclick: setAceSetTheme},
  {text: \'TextMate\', onclick: setAceSetTheme},
  {text: \'Tomorrow\', onclick: setAceSetTheme},
  {text: \'XCode\', onclick: setAceSetTheme},
  {text: \'Kuroir\', onclick: setAceSetTheme},
  {text: \'KatzenMilch\', onclick: setAceSetTheme},
  {text: \'SQL Server\', onclick: setAceSetTheme},
  {text: \'---- Dark ----\', disabled: true, selected: false},
  {text: \'Ambiance\', onclick: setAceSetTheme},
  {text: \'Chaos\', onclick: setAceSetTheme},
  {text: \'Clouds Midnight\', onclick: setAceSetTheme},
  {text: \'Cobalt\', onclick: setAceSetTheme},
  {text: \'idle Fingers\', onclick: setAceSetTheme},
  {text: \'krTheme\', onclick: setAceSetTheme},
  {text: \'Merbivore\', onclick: setAceSetTheme},
  {text: \'Merbivore Soft\', onclick: setAceSetTheme},
  {text: \'Mono Industrial\', onclick: setAceSetTheme},
  {text: \'Monokai\', onclick: setAceSetTheme},
  {text: \'Pastel on dark\', onclick: setAceSetTheme},
  {text: \'Solarized Dark\', onclick: setAceSetTheme},
  {text: \'Terminal\', onclick: setAceSetTheme},
  {text: \'Tomorrow Night\', onclick: setAceSetTheme},
  {text: \'Tomorrow Night Blue\', onclick: setAceSetTheme},
  {text: \'Tomorrow Night Bright\', onclick: setAceSetTheme},
  {text: \'Tomorrow Night Eighties\', onclick: setAceSetTheme},
  {text: \'Twilight\', onclick: setAceSetTheme},
  {text: \'Vibrant Ink\', onclick: setAceSetTheme}
];

// kitchen sink toolbar ini
function aceMoreSuperOptions(){
  if(typeof tinymce !== "undefined" && !$("#aceMoreSuperOptions_wrapper").length){
    $("body").append("<div id=aceMoreSuperOptions_wrapper><div id=aceMoreSuperOptions_wrapper_boss></div><div style=display:none; id=aceMoreSuperOptions_wrapper_sub></div></div>");
    tinymce.ui.Factory.create({
      type: "button",
      icon: "fullpage",
      classes:"moreAceAttr",
      tooltip: "Ace Settings...",
      onPostRender:function(){
        $(".mce-moreAceAttr").attr("class","mce-moreAceAttr");
        $(".mce-moreAceAttr button").contents().unwrap();
      },
      onclick: function(){
        if($(\'#aceMoreSuperOptions_wrapper_sub:hidden\').length){
          $(\'#aceMoreSuperOptions_wrapper_sub\').show()
          $(\'.mce-moreAceAttr\').addClass(\'mce-active\')
        }
        else{
          $(\'#aceMoreSuperOptions_wrapper_sub\').hide()
          $(\'.mce-moreAceAttr\').removeClass(\'mce-active\')
        }
      }
    }).renderTo(document.getElementById("aceMoreSuperOptions_wrapper_boss"));
    tinymce.ui.Factory.create({
      type: \'button\',
      icon: \'fullscreen\',
      classes: \'aceMoreSuperOptionsFS\',
      tooltip: \'Fullscreen\',
      onclick: function(){
        if(getAceCredential().thisWrap.hasClass("ace_editor_fullScreen")){
          getAceCredential().thisWrap.removeClass("ace_editor_fullScreen");
          $("#aceMoreSuperOptions_wrapper").removeClass("ace_editor_fullScreen");
          ace.edit(getAceCredential().thisAceId).resize();
        }
        else{
          getAceCredential().thisWrap.addClass("ace_editor_fullScreen")
          $("#aceMoreSuperOptions_wrapper").addClass("ace_editor_fullScreen");
          ace.edit(getAceCredential().thisAceId).resize();
        }
      },
      onPostRender:function(){
        // $(".mce-aceMoreSuperOptions .mce-caret").remove();
        $(document).on("mouseenter click", ".ace_wrapper_modx", function () {
          if(!$(this).find("#aceMoreSuperOptions_wrapper").length){
            $("#aceMoreSuperOptions_wrapper").prependTo($(this)).fadeIn();
            aceMoreSuperOptions();
          }
        })
      }
    }).renderTo(document.getElementById("aceMoreSuperOptions_wrapper_sub"));
    tinymce.ui.Factory.create({
      type: \'listbox\',
      icon: \'code\',
      classes: \'aceMoreSuperOptionsMODE\',
      tooltip: \'Syntax / Mode\',
      menu: aceMoreSuperOptionsToolButtonMODE,
      onPostRender: function(){
        $(".mce-aceMoreSuperOptionsMODE .mce-caret").remove();
        $(".mce-aceMoreSuperOptionsMODE .mce-txt").remove();
      },
      onselect: function(){
        ace.edit(getAceCredential().thisAceId).getSession().setMode("ace/mode/"+this.value())
        ace.edit(getAceCredential().thisAceId).resize();
        getAceCredential().thisSink.text(this.value())
        $(".mce-aceMoreSuperOptionsMODE .mce-txt").remove();
      }
    }).renderTo(document.getElementById("aceMoreSuperOptions_wrapper_sub"));

    tinymce.ui.Factory.create({
      // icon: "fullpage",
      type: \'menubutton\',
      classes: \'aceMoreSuperOptionsMORE\',
      tooltip: \'Themes and more Options\',
      onPostRender:function(){
        // $(".mce-aceMoreSuperOptionsMORE .mce-caret").remove();
      },
      menu: [
      {
        text: "Show Invisibles",
        classes: "setShowInvisibles",
        onclick: function(){
          if(ace.edit(getAceCredential().thisAceId).getOption("showInvisibles")){
            ace.edit(getAceCredential().thisAceId).setShowInvisibles(false);
          }
          else{
            ace.edit(getAceCredential().thisAceId).setShowInvisibles(true);
          }
        }
      },
      {
        text: "Scroll Past End",
        classes: "setScroll",
        onclick: function(){
          if(ace.edit(getAceCredential().thisAceId).getOption("scrollPastEnd")){
            ace.edit(getAceCredential().thisAceId).setOption("scrollPastEnd", false);
            ace.edit(getAceCredential().thisAceId).resize();
          }
          else{
            ace.edit(getAceCredential().thisAceId).setOption("scrollPastEnd", true);
            ace.edit(getAceCredential().thisAceId).resize();
          }
        }
      },
      {
        text: "Refresh Editor",
        onclick: function(){
          ace.edit(getAceCredential().thisAceId).resize();
        }
      },
      {
        text: "Soft Wrap",
        menu: aceMoreSuperOptionsToolButtonWRAP
      },
      {
        text: "Max Height",
        menu: aceMoreSuperOptionsToolButtonHEIGHT
      },
      {
        text: "Theme",
        menu: aceMoreSuperOptionsToolButtonTHEME
      },
      {
        text: "Font Size",
        menu: aceMoreSuperOptionsToolButtonFONT,
      },
      ],
    }).renderTo(document.getElementById("aceMoreSuperOptions_wrapper_sub"));
    if(tinymce.editors.length < 1){
      // fileManagerTopNavModalSkin = MODx.config.assets_url+"components/tinymcewrapper/tinymceskins/modxPericles/"+"skin.min.css";
      tinymce.DOM.loadCSS(fileManagerTopNavModalSkin+"/skin.min.css");
    }
  }
}
// ************************** END Kitchen Sink **************************


/*#######################################################################################
   *************** MODX INIT : load applicable JS and CSS files ***************
#######################################################################################*/
Ext.Loader.load([
  \'[[++assets_url]]components/tinymcewrapper/tinymceplugins/ace/emmet.min.js\',
  \'[[+AceSrc]]ext-emmet.js\',
  \'[[+AceSrc]]ext-modelist.js\',
 ],
  function() {
    Ext.onReady(function () {
      aceTextareas();
    // }) // use this instead of delay
    },this,{delay:50}) // delay 100 always works well
  }
)
emmetForAceIsLoaded = true; // this helps other Ace implementations to piggy-back on this emmet
',
    ),
  ),
  '4902148f205673f14d9472ec9ef24bdc' => 
  array (
    'criteria' => 
    array (
      'name' => 'sample.TinymceWrapperMarkdown',
    ),
    'object' => 
    array (
      'id' => 30,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'sample.TinymceWrapperMarkdown',
      'description' => 'Transform any TinyMCE editor into a powerful and beautiful Markdown Editor. Works with twExoticMarkdownEditor.js, allows floating tinymceBubbleBar.js toolbar, contextmenu, modxMagicHoverLink, twPreCodeManager.js',
      'editor_type' => 0,
      'category' => 9,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  selector: "textarea",
  //inline: true, //for contenteditable selector
  forced_root_block : "", //!important
  force_br_newlines : true, //!important
  force_p_newlines : false, //!important
  valid_elements: "br", //!important
  paste_as_text: true, //!important
  external_plugins: {
    twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js", //!important
    bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js", //!important
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
  },
  twExoticMarkdownSettings: {
    addClass: false, //default is true (add .twExoticMarkdownEditor class to editor body)
    removeClasses: "a b c", // remove class(es) when editor loads - smoothly reveal editor only after text has been prepared
    skipClass: "", // do not process this particular editor content, skip it!
    addAttr: false, //default is true (add markdown="1" to editor body) good for parsing mixed content in mark/parsedownExtra
  },
  plugins: "paste contextmenu save searchreplace", //!important
  toolbar: "modxMagicHoverLink twPreCodeManager boldMD italicMD linkMD imageMD blockquoteMD codeMD numlistMD bullistMD tableMD undo redo searchreplace bubbleBarOptionsButton", //!important
  menubar: false,
  contextmenu: "modxMagicHoverLink twPreCodeManager boldMD italicMD linkMD imageMD blockquoteMD codeMD numlistMD bullistMD tableMD undo redo searchreplace template" //!important
})',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  selector: "textarea",
  //inline: true, //for contenteditable selector
  forced_root_block : "", //!important
  force_br_newlines : true, //!important
  force_p_newlines : false, //!important
  valid_elements: "br", //!important
  paste_as_text: true, //!important
  external_plugins: {
    twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js", //!important
    bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js", //!important
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
  },
  twExoticMarkdownSettings: {
    addClass: false, //default is true (add .twExoticMarkdownEditor class to editor body)
    removeClasses: "a b c", // remove class(es) when editor loads - smoothly reveal editor only after text has been prepared
    skipClass: "", // do not process this particular editor content, skip it!
    addAttr: false, //default is true (add markdown="1" to editor body) good for parsing mixed content in mark/parsedownExtra
  },
  plugins: "paste contextmenu save searchreplace", //!important
  toolbar: "modxMagicHoverLink twPreCodeManager boldMD italicMD linkMD imageMD blockquoteMD codeMD numlistMD bullistMD tableMD undo redo searchreplace bubbleBarOptionsButton", //!important
  menubar: false,
  contextmenu: "modxMagicHoverLink twPreCodeManager boldMD italicMD linkMD imageMD blockquoteMD codeMD numlistMD bullistMD tableMD undo redo searchreplace template" //!important
})',
    ),
  ),
  'da6f0f65bf864d6bd6e1ecb6f8c5e9a2' => 
  array (
    'criteria' => 
    array (
      'category' => '3rdPartyChunks',
    ),
    'object' => 
    array (
      'id' => 10,
      'parent' => 9,
      'category' => '3rdPartyChunks',
      'rank' => 0,
    ),
  ),
  '4f9c62776771697e46aacb3d8b3e45d2' => 
  array (
    'criteria' => 
    array (
      'name' => 'sample.TinymceWrapperMIGX',
    ),
    'object' => 
    array (
      'id' => 31,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'sample.TinymceWrapperMIGX',
      'description' => 'TinyMCE for MIGX textareas. Thanks to Bruno17. Please remove the prefix "sample." before use and to preserve this custom chunk when you upgrade TinymceWrapper. This chunk DOES NOT accept a custom suffix.',
      'editor_type' => 0,
      'category' => 10,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  mode: "exact",
  elements: "tv[[+tv_id]]",
  [[$TinymceWrapperCommonCode]]
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,link,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  valid_elements: "iframe[*],object[*],audio[*],-span[!title|!class<test test2],a[href|target|class|rel|title|data-ajax|data-iframe],strong,b,-p[class<text-align-left?text-align-center?text-align-right],br,-h1[class|data-ajax|data-iframe],-h2[class|data-ajax|data-iframe],-h3[class|data-ajax|data-iframe],-img[!src|!alt|!class=round_img|data-ajax|data-iframe],em,-blockquote,pre[class],-ol,-ul,-li,-code[class]",
  valid_children: "-li[ul],-li[ol],-li[div],-strong[*],-em[*],-h1[*],-h2[*],-h3[*],-a[strong|em|h1|h2|h3|p|div],blockquote[p|ol|ul],pre,div",
  resize: true,
  autoresize_min_height: 100,
  autoresize_max_height: 400,
  toolbar: "newdocument | fullscreen preview | undo redo | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect | charmap emoticons insertdatetime | searchreplace",
  contextmenu: "removeformat | link | image | code",
  setup: function(editor) {
    editor.on(\'mouseleave\', function(evt) {
      tinyMCE.activeEditor.save();
    });
  }
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  mode: "exact",
  elements: "tv[[+tv_id]]",
  [[$TinymceWrapperCommonCode]]
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,link,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  valid_elements: "iframe[*],object[*],audio[*],-span[!title|!class<test test2],a[href|target|class|rel|title|data-ajax|data-iframe],strong,b,-p[class<text-align-left?text-align-center?text-align-right],br,-h1[class|data-ajax|data-iframe],-h2[class|data-ajax|data-iframe],-h3[class|data-ajax|data-iframe],-img[!src|!alt|!class=round_img|data-ajax|data-iframe],em,-blockquote,pre[class],-ol,-ul,-li,-code[class]",
  valid_children: "-li[ul],-li[ol],-li[div],-strong[*],-em[*],-h1[*],-h2[*],-h3[*],-a[strong|em|h1|h2|h3|p|div],blockquote[p|ol|ul],pre,div",
  resize: true,
  autoresize_min_height: 100,
  autoresize_max_height: 400,
  toolbar: "newdocument | fullscreen preview | undo redo | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect | charmap emoticons insertdatetime | searchreplace",
  contextmenu: "removeformat | link | image | code",
  setup: function(editor) {
    editor.on(\'mouseleave\', function(evt) {
      tinyMCE.activeEditor.save();
    });
  }
});',
    ),
  ),
  '6566297e14c3a3c430f72fc59b8a5e7e' => 
  array (
    'criteria' => 
    array (
      'category' => 'Frontend',
    ),
    'object' => 
    array (
      'id' => 11,
      'parent' => 8,
      'category' => 'Frontend',
      'rank' => 0,
    ),
  ),
  '5cb6cf6b5a0f1c09fc10edd5e7ae51ff' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPcontent',
    ),
    'object' => 
    array (
      'id' => 32,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPcontent',
      'description' => 'for NewsPublisher np-content',
      'editor_type' => 0,
      'category' => 11,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  name: "Article Main Body", //also used for save form validation
  selector: "[data-tiny=np-content]",
  [[+commonTinyMCECode]]
  // forced_root_block: \'\',
  // force_br_newlines: false,
  // force_p_newlines: false,
  plugins: "twPreCodeManager imagetools contextmenu autosave save paste image link searchreplace",
  paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  toolbar: "code codesample | blockquote bold italic underline | link unlink | image | bubbleBarOptionsButton",
  menubar: false,
  contextmenu: "code twPreCodeManager reset undo redo bold italic | link unlink | removeformat image"
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  name: "Article Main Body", //also used for save form validation
  selector: "[data-tiny=np-content]",
  [[+commonTinyMCECode]]
  // forced_root_block: \'\',
  // force_br_newlines: false,
  // force_p_newlines: false,
  plugins: "twPreCodeManager imagetools contextmenu autosave save paste image link searchreplace",
  paste_word_valid_elements: "a,div,b,strong,i,em,h1,h2,h3,p,blockquote,ol,ul,pre",
  toolbar: "code codesample | blockquote bold italic underline | link unlink | image | bubbleBarOptionsButton",
  menubar: false,
  contextmenu: "code twPreCodeManager reset undo redo bold italic | link unlink | removeformat image"
});',
    ),
  ),
  'c055cf465b4895cd70bb7e9fce1ce8b0' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPpagetitle',
    ),
    'object' => 
    array (
      'id' => 33,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPpagetitle',
      'description' => 'for NewsPublisher np-pagetitle',
      'editor_type' => 0,
      'category' => 11,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  name: "tw_magic_create_page",
  selector: "[data-tiny=np-pagetitle]",
  [[+commonTinyMCECode]]
  plugins: "autosave save contextmenu",
  paste_word_valid_elements: "h2",
  menubar: false,
  toolbar: false,
  //toolbar:"undo redo | save | close bubbleBarOptionsButton",
  //invalid_elements: "*",
  //valid_elements: "",
  //valid_children: "",
  contextmenu: "reset undo redo"
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  name: "tw_magic_create_page",
  selector: "[data-tiny=np-pagetitle]",
  [[+commonTinyMCECode]]
  plugins: "autosave save contextmenu",
  paste_word_valid_elements: "h2",
  menubar: false,
  toolbar: false,
  //toolbar:"undo redo | save | close bubbleBarOptionsButton",
  //invalid_elements: "*",
  //valid_elements: "",
  //valid_children: "",
  contextmenu: "reset undo redo"
});',
    ),
  ),
  'c589408cca8c8a1edea842c94dc83452' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPfileRichTv',
    ),
    'object' => 
    array (
      'id' => 34,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPfileRichTv',
      'description' => 'for NewsPublisher np-imageRichTv',
      'editor_type' => 0,
      'category' => 11,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  selector: "[data-tiny^=np-fileRichTv],[data-tiny^=np-imageRichTv]",
  [[+commonTinyMCECode]]
  plugins: "autosave save contextmenu",
  paste_word_valid_elements: "h2",
  menubar: false,
  toolbar: false,
  invalid_elements: "*",
  valid_elements: "h2[]",
  valid_children: "h2[]",
  contextmenu: "save image undo redo"
});',
      'locked' => 0,
      'properties' => NULL,
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  selector: "[data-tiny^=np-fileRichTv],[data-tiny^=np-imageRichTv]",
  [[+commonTinyMCECode]]
  plugins: "autosave save contextmenu",
  paste_word_valid_elements: "h2",
  menubar: false,
  toolbar: false,
  invalid_elements: "*",
  valid_elements: "h2[]",
  valid_children: "h2[]",
  contextmenu: "save image undo redo"
});',
    ),
  ),
  '376c2dd14cf2a8982578bcb04005a5c4' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPintrotext',
    ),
    'object' => 
    array (
      'id' => 35,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPintrotext',
      'description' => 'for NewsPublisher np-introtext',
      'editor_type' => 0,
      'category' => 11,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  name: "Article Summary",
  selector: "[data-tiny=np-introtext]",
  [[+commonTinyMCECode]]
  plugins: "paste autosave save contextmenu",
  paste_word_valid_elements: "",
  menubar: false,
  toolbar: false,
  //toolbar:"undo redo bubbleBarOptionsButton",
  invalid_elements: "*",
  valid_elements: "a strong b",
  valid_children: "",
  contextmenu: "reset undo redo"
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  name: "Article Summary",
  selector: "[data-tiny=np-introtext]",
  [[+commonTinyMCECode]]
  plugins: "paste autosave save contextmenu",
  paste_word_valid_elements: "",
  menubar: false,
  toolbar: false,
  //toolbar:"undo redo bubbleBarOptionsButton",
  invalid_elements: "*",
  valid_elements: "a strong b",
  valid_children: "",
  contextmenu: "reset undo redo"
});',
    ),
  ),
  'e8599150953513be95db68691faf53c0' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPTinymceWrapperServiceTV1',
    ),
    'object' => 
    array (
      'id' => 36,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPTinymceWrapperServiceTV1',
      'description' => 'for NewsPublisher TVs starting with np-TinymceWrapperService...',
      'editor_type' => 0,
      'category' => 11,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  name: "Service TVs",
  selector: "[data-tiny^=np-TinymceWrapperService]",
  [[+commonTinyMCECode]]
  forced_root_block: \'\',
  force_br_newlines: false,
  force_p_newlines: false,
  plugins: "contextmenu autosave save paste image link searchreplace",
  paste_word_valid_elements: "i,h3,p",
  valid_elements: "i[*],h3[*],p[*]",
  toolbar: "code bold italic underline image bubbleBarOptionsButton",
  menubar: false,
  contextmenu: "code reset undo redo bold italic link unlink | removeformat | image"
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  name: "Service TVs",
  selector: "[data-tiny^=np-TinymceWrapperService]",
  [[+commonTinyMCECode]]
  forced_root_block: \'\',
  force_br_newlines: false,
  force_p_newlines: false,
  plugins: "contextmenu autosave save paste image link searchreplace",
  paste_word_valid_elements: "i,h3,p",
  valid_elements: "i[*],h3[*],p[*]",
  toolbar: "code bold italic underline image bubbleBarOptionsButton",
  menubar: false,
  contextmenu: "code reset undo redo bold italic link unlink | removeformat | image"
});',
    ),
  ),
  '6761670a093945337d46e0d7779b3ae5' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPTinymceWrapperCategoryTV1',
    ),
    'object' => 
    array (
      'id' => 37,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPTinymceWrapperCategoryTV1',
      'description' => 'for NewsPublisher TVs starting with np-TinymceWrapperCategoryTV...',
      'editor_type' => 0,
      'category' => 11,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  name: "Category TVs",
  selector: "[data-tiny^=np-TinymceWrapperCategoryTV]",
  [[+commonTinyMCECode]]
  fixed_toolbar_container: false,
  forced_root_block: \'\',
  force_br_newlines: false,
  force_p_newlines: false,
  plugins: "contextmenu autosave save paste link",
  paste_word_valid_elements: "i,h3,p",
  valid_elements: "i[*],h3[*],p[*]",
  setup: function(editor) {
    editor.addButton("changeBG", {
    text:"BG",
    icon:"image",
    tooltip:"Change BG image",
    onclick:function(){
      var fieldN = $("#"+editor.id).parents("a").find("[data-tiny-image]").attr("data-tiny-image")
      autoFileBrowser(fieldN,"", "", "", 1)
   }
  });
   },
  toolbar: "changeBG code",
  twCodeMirrorPoppedOrInline: 1, //default is 1 = popped
  menubar: false,
  contextmenu: "code reset undo redo"
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  name: "Category TVs",
  selector: "[data-tiny^=np-TinymceWrapperCategoryTV]",
  [[+commonTinyMCECode]]
  fixed_toolbar_container: false,
  forced_root_block: \'\',
  force_br_newlines: false,
  force_p_newlines: false,
  plugins: "contextmenu autosave save paste link",
  paste_word_valid_elements: "i,h3,p",
  valid_elements: "i[*],h3[*],p[*]",
  setup: function(editor) {
    editor.addButton("changeBG", {
    text:"BG",
    icon:"image",
    tooltip:"Change BG image",
    onclick:function(){
      var fieldN = $("#"+editor.id).parents("a").find("[data-tiny-image]").attr("data-tiny-image")
      autoFileBrowser(fieldN,"", "", "", 1)
   }
  });
   },
  toolbar: "changeBG code",
  twCodeMirrorPoppedOrInline: 1, //default is 1 = popped
  menubar: false,
  contextmenu: "code reset undo redo"
});',
    ),
  ),
  'cced86c4ac1ef47dfca32e3935d10985' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPTinymceWrapperMiscTV1',
    ),
    'object' => 
    array (
      'id' => 38,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPTinymceWrapperMiscTV1',
      'description' => 'for NewsPublisher TVs starting with np-TinymceWrapperMiscTV...',
      'editor_type' => 0,
      'category' => 11,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  name: "Misc TVs",
  selector: "[data-tiny^=np-TinymceWrapperMiscTV]",
  [[+commonTinyMCECode]]
  forced_root_block: \'\',
  force_br_newlines: false,
  force_p_newlines: false,
  plugins: "contextmenu code autosave save paste image link searchreplace",
  valid_elements: "*[*],i[*]",
  extended_valid_elements : "hr[*],+i[*]",
  toolbar: "code bold italic underline link unlink blockquote image bubbleBarOptionsButton",
  menubar: false,
  contextmenu: "reset undo redo bold italic link unlink | removeformat | image"
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  name: "Misc TVs",
  selector: "[data-tiny^=np-TinymceWrapperMiscTV]",
  [[+commonTinyMCECode]]
  forced_root_block: \'\',
  force_br_newlines: false,
  force_p_newlines: false,
  plugins: "contextmenu code autosave save paste image link searchreplace",
  valid_elements: "*[*],i[*]",
  extended_valid_elements : "hr[*],+i[*]",
  toolbar: "code bold italic underline link unlink blockquote image bubbleBarOptionsButton",
  menubar: false,
  contextmenu: "reset undo redo bold italic link unlink | removeformat | image"
});',
    ),
  ),
  '92590bd545a08f65ba191c6662532735' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPtraditional',
    ),
    'object' => 
    array (
      'id' => 39,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPtraditional',
      'description' => 'All TinyMCE init in one chunk - for the old school method of using RTE with NewsPublisher. Use this if the TinyMagicPublisher magic is too much. You can also place [[$chunks]] within this chunk for readability',
      'editor_type' => 0,
      'category' => 11,
      'cache_type' => 0,
      'snippet' => 'tinymce.init({
  selector: "#np-introtext",
  skin_url: \'[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles\',
  menubar: false,
  statusbar: false,
  external_plugins: {
    twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
    // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
    //bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
    // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
  },
  file_browser_callback: autoFileBrowser,
  plugins: "contextmenu,charmap,paste,link,preview,code,insertdatetime",
  toolbar: "twPreCodeManager | undo redo | newdocument | preview code | styleselect | blockquote | bold | italic | bullist numlist | link unlink | charmap | insertdatetime",
  contextmenu: "twPreCodeManager link code styleselect",
  resize: true
});


tinymce.init({
  selector: "#np-description",
  skin_url: \'[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles\',
  menubar: false,
  statusbar: false,
  image_advtab: true,
  external_plugins: {
    twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
    // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
    //bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
    // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
  },
  file_browser_callback: autoFileBrowser,
  plugins: "contextmenu,charmap,paste,preview,code,insertdatetime",
  toolbar: "twPreCodeManager | undo redo | newdocument | preview code | styleselect | blockquote | bold | italic | bullist numlist | link unlink | charmap | insertdatetime",
  contextmenu: "twPreCodeManager link code styleselect",
  resize: true
});


tinymce.init({
  selector: "#np-content",
  skin_url: \'[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles\',
  menubar: false,
  external_plugins: {
    twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
    // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
    //bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
    // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
  },
  file_browser_callback: autoFileBrowser,
  statusbar: true,
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  resize: true,
  autoresize_min_height: 100,
  toolbar: "twPreCodeManager | preview fullscreen | code | undo redo | newdocument | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect | charmap emoticons insertdatetime | searchreplace",
  contextmenu: "twPreCodeManager code | fullscreen | removeformat | link | image"
});


tinymce.init({
  mode: "exact", //DO NOT TOUCH
  selector: "textarea:not(#np-content, #np-introtext, #np-description, #np-TinyJSONGalleryTV)", //all richtext TVs basically
  skin_url: \'[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles\',
  menubar: false,
  statusbar: false,
  external_plugins: {
    twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
    // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
    //bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
    // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
  },
  file_browser_callback: autoFileBrowser,
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  resize: true,
  autoresize_min_height: 100,
  autoresize_max_height: 400,
  toolbar: "twPreCodeManager | preview fullscreen | code | newdocument | undo redo | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect | charmap emoticons insertdatetime | searchreplace",
  contextmenu: "twPreCodeManager code | removeformat | link | image"
});',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'tinymce.init({
  selector: "#np-introtext",
  skin_url: \'[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles\',
  menubar: false,
  statusbar: false,
  external_plugins: {
    twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
    // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
    //bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
    // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
  },
  file_browser_callback: autoFileBrowser,
  plugins: "contextmenu,charmap,paste,link,preview,code,insertdatetime",
  toolbar: "twPreCodeManager | undo redo | newdocument | preview code | styleselect | blockquote | bold | italic | bullist numlist | link unlink | charmap | insertdatetime",
  contextmenu: "twPreCodeManager link code styleselect",
  resize: true
});


tinymce.init({
  selector: "#np-description",
  skin_url: \'[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles\',
  menubar: false,
  statusbar: false,
  image_advtab: true,
  external_plugins: {
    twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
    // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
    //bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
    // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
  },
  file_browser_callback: autoFileBrowser,
  plugins: "contextmenu,charmap,paste,preview,code,insertdatetime",
  toolbar: "twPreCodeManager | undo redo | newdocument | preview code | styleselect | blockquote | bold | italic | bullist numlist | link unlink | charmap | insertdatetime",
  contextmenu: "twPreCodeManager link code styleselect",
  resize: true
});


tinymce.init({
  selector: "#np-content",
  skin_url: \'[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles\',
  menubar: false,
  external_plugins: {
    twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
    // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
    //bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
    // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
  },
  file_browser_callback: autoFileBrowser,
  statusbar: true,
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  resize: true,
  autoresize_min_height: 100,
  toolbar: "twPreCodeManager | preview fullscreen | code | undo redo | newdocument | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect | charmap emoticons insertdatetime | searchreplace",
  contextmenu: "twPreCodeManager code | fullscreen | removeformat | link | image"
});


tinymce.init({
  mode: "exact", //DO NOT TOUCH
  selector: "textarea:not(#np-content, #np-introtext, #np-description, #np-TinyJSONGalleryTV)", //all richtext TVs basically
  skin_url: \'[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles\',
  menubar: false,
  statusbar: false,
  external_plugins: {
    twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
    // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
    //bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
    // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
    modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
    twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
  },
  file_browser_callback: autoFileBrowser,
  plugins: "autoresize,preview,paste,contextmenu,image,wordcount,fullscreen,code,charmap,searchreplace,textpattern,emoticons,insertdatetime",
  resize: true,
  autoresize_min_height: 100,
  autoresize_max_height: 400,
  toolbar: "twPreCodeManager | preview fullscreen | code | newdocument | undo redo | blockquote | bold | italic | aligncenter | bullist numlist | link unlink | image | styleselect | charmap emoticons insertdatetime | searchreplace",
  contextmenu: "twPreCodeManager code | removeformat | link | image"
});',
    ),
  ),
  'e9c62ce10f6a6ad2a4f3398254957399' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperNPCommonCode',
    ),
    'object' => 
    array (
      'id' => 40,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperNPCommonCode',
      'description' => 'for NewsPublisher TMP TinyMCE inits',
      'editor_type' => 0,
      'category' => 11,
      'cache_type' => 0,
      'snippet' => 'inline: true,
fixed_toolbar_container: "#tinymceWrapperBubbleNP",
hidden_input: false,
skin_url: "[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles",
save_enablewhendirty: false,
save_onsavecallback: saveArticle,
file_browser_callback: autoFileBrowser,
browser_spellcheck: true,
gecko_spellcheck: true,
paste_data_images: false,
external_plugins: {
  // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
  // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
  twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
  bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
  modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
  twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
},
link_list:[
  [[$TinymceWrapperLinkList]]
],
//leave trailing comma',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'static' => 0,
      'static_file' => '',
      'content' => 'inline: true,
fixed_toolbar_container: "#tinymceWrapperBubbleNP",
hidden_input: false,
skin_url: "[[++assets_url]]components/tinymcewrapper/tinymceskins/modxPericles",
save_enablewhendirty: false,
save_onsavecallback: saveArticle,
file_browser_callback: autoFileBrowser,
browser_spellcheck: true,
gecko_spellcheck: true,
paste_data_images: false,
external_plugins: {
  // twExoticMarkdownEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twExoticMarkdownEditor.js",
  // twCodeMirror: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twCodeMirror.js",
  twAceEditor: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twAceEditor.js",
  bubbleBar: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/tinymceBubbleBar.js",
  modxMagicHoverLink: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/modxMagicHoverLink.js",
  twPreCodeManager: "[[++assets_url]]components/tinymcewrapper/tinymceplugins/twPreCodeManager.js"
},
link_list:[
  [[$TinymceWrapperLinkList]]
],
//leave trailing comma',
    ),
  ),
  '43f566c38d0af8f21e78e9f4e994cffd' => 
  array (
    'criteria' => 
    array (
      'category' => 'npCustomFormTpl',
    ),
    'object' => 
    array (
      'id' => 12,
      'parent' => 11,
      'category' => 'npCustomFormTpl',
      'rank' => 0,
    ),
  ),
  'e84cf284f0474ae4beef72273a9a1cbc' => 
  array (
    'criteria' => 
    array (
      'category' => 'TinyUtilities',
    ),
    'object' => 
    array (
      'id' => 13,
      'parent' => 1,
      'category' => 'TinyUtilities',
      'rank' => 0,
    ),
  ),
  '7fdc1003f280ee45b0fcf3150ce860a2' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperGRF',
    ),
    'object' => 
    array (
      'id' => 9,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperGRF',
      'description' => 'TinymceWrapper Plugin uses GetResourceField to grab the url of your elFinder resource. Since I don\'t know if you have friendly url on or not, I had no choice. Please replace this in your Plugin property, enter the url of your elFinder resource.',
      'editor_type' => 0,
      'category' => 13,
      'cache_type' => 0,
      'snippet' => '$resource = $modx->getObject(\'modResource\', array($kF =>$kFv));
if($resource){
	$output = $resource->get($gNuFv);
return $output;
	}',
      'locked' => 0,
      'properties' => 'a:3:{s:5:"gNuFv";a:7:{s:4:"name";s:5:"gNuFv";s:4:"desc";s:23:"getNewUnknownFieldValue";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:0:"";}s:2:"kF";a:7:{s:4:"name";s:2:"kF";s:4:"desc";s:10:"knownField";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:0:"";}s:3:"kFv";a:7:{s:4:"name";s:3:"kFv";s:4:"desc";s:15:"knownFieldValue";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:0:"";}}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '$resource = $modx->getObject(\'modResource\', array($kF =>$kFv));
if($resource){
	$output = $resource->get($gNuFv);
return $output;
	}',
    ),
  ),
  'fb55a7be2e8067045655f5b50e4593f0' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperGetUrlParam',
    ),
    'object' => 
    array (
      'id' => 10,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperGetUrlParam',
      'description' => 'This extends your elFinder, gives you url parameter emperor status',
      'editor_type' => 0,
      'category' => 13,
      'cache_type' => 0,
      'snippet' => '/**
 *
 * forked from getUrlParam by Paul Merchant
 *
 * A simple snippet to return a value passed through a URL parameter.
 *
 * OPTIONS
 * name - The parameter name, defaults to p
 * int - (Opt) Set as true to only allow integer values
 * max - (Opt) The maximum length of the returned value, defaults to 20
 * default - (Opt) The value returned if no URL parameter is found
 *
 * Example: [[TinymceWrapperGetUrlParam? &name=`pageid` &int=`1`]]
 *
**/

// set defaults
$name = $modx->getOption(\'name\',$scriptProperties,\'p\');
$int = $modx->getOption(\'int\',$scriptProperties,false);
$max = $modx->getOption(\'max\',$scriptProperties,20);
$output = $modx->getOption(\'default\',$scriptProperties,\'\');

// get the sanitized value if there is one
if (isset($_GET[$name])) {
    if ($int) {
        $value = intval($_GET[$name]);
    } else {
        if (strlen($_GET[$name]) > $max) {
            $value = filter_var(substr($_GET[$name],0,$max), FILTER_SANITIZE_STRING);
        } else {
            $value = filter_var($_GET[$name], FILTER_SANITIZE_STRING);
        }
    }

    $output = rawurldecode($value);
		if($name == \'onlyMimes\'){
		$output = \'["\'.$output.\'"]\';
	}
		if($name == \'user\'){
		$output = \'?name=\'.$output;
	}
}

return $output;',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '/**
 *
 * forked from getUrlParam by Paul Merchant
 *
 * A simple snippet to return a value passed through a URL parameter.
 *
 * OPTIONS
 * name - The parameter name, defaults to p
 * int - (Opt) Set as true to only allow integer values
 * max - (Opt) The maximum length of the returned value, defaults to 20
 * default - (Opt) The value returned if no URL parameter is found
 *
 * Example: [[TinymceWrapperGetUrlParam? &name=`pageid` &int=`1`]]
 *
**/

// set defaults
$name = $modx->getOption(\'name\',$scriptProperties,\'p\');
$int = $modx->getOption(\'int\',$scriptProperties,false);
$max = $modx->getOption(\'max\',$scriptProperties,20);
$output = $modx->getOption(\'default\',$scriptProperties,\'\');

// get the sanitized value if there is one
if (isset($_GET[$name])) {
    if ($int) {
        $value = intval($_GET[$name]);
    } else {
        if (strlen($_GET[$name]) > $max) {
            $value = filter_var(substr($_GET[$name],0,$max), FILTER_SANITIZE_STRING);
        } else {
            $value = filter_var($_GET[$name], FILTER_SANITIZE_STRING);
        }
    }

    $output = rawurldecode($value);
		if($name == \'onlyMimes\'){
		$output = \'["\'.$output.\'"]\';
	}
		if($name == \'user\'){
		$output = \'?name=\'.$output;
	}
}

return $output;',
    ),
  ),
  'bef30f6375cde30919a2e5dd8f928d02' => 
  array (
    'criteria' => 
    array (
      'name' => 'TinymceWrapperMarkdown',
    ),
    'object' => 
    array (
      'id' => 11,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'TinymceWrapperMarkdown',
      'description' => 'Output modifier. It can also be used with other modifiers.
[[*content:TinymceWrapperMarkdown]] ... [[+placeholder:TinymceWrapperMarkdown]]
TinymceWrapperMarkdown = `markdownE` or `parsedown` default is parsedownE',
      'editor_type' => 0,
      'category' => 13,
      'cache_type' => 0,
      'snippet' => '/*
by donshakespeare
TinymceWrapperMarkdown is an Output Modifier for MODX
[[*content:TinymceWrapperMarkdown=`markdown`]]
or `markdownE` or `parsedown`
default =`parsedownE`
parsedownE or markdownE is good for Markdown mixed with HTML, and Markdown enclosed in HTML markdown="1"*/
if($input){
    $content = $input;
    // Parse MODX tags first || parse BBCodes here too
    $content = $modx->newObject(\'modChunk\')->process(null, $content);
    $options = explode(\',\',$options);
    if($options[0] == "markdown"){
        if (!class_exists(\'\\Michelf\\Markdown\')) {
            require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/Michelf/Markdown.inc.php\';
        }
        $content = \\Michelf\\Markdown::defaultTransform($content);
    }
    elseif($options[0] == "markdownE"){
        if (!class_exists(\'\\Michelf\\MarkdownExtra\')) {
            require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/Michelf/MarkdownExtra.inc.php\';
        }
        $content = \\Michelf\\MarkdownExtra::defaultTransform($content);
    }
    elseif($options[0] == "parsedown"){
        require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/parsedown/Parsedown.php\';
        $Parsedown = new Parsedown();
        $content = $Parsedown->text($content);
    }
    else{ //default state
        $options[0] = "parsedownExtra";
        require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/parsedown/Parsedown.php\';
        require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/parsedown/ParsedownExtra.php\';
        $ParsedownExtra = new ParsedownExtra();
        $content = $ParsedownExtra->text($content);
    }
    if(isset($options[1])){
        $squareBracketSeparator = substr($options[1], 0, 1);
        $content = str_replace(\'[\'.$squareBracketSeparator.\'[\', \'&#91;&#91;\', $content); // do some more find/replace
    }
    return $content);
}',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '/*
by donshakespeare
TinymceWrapperMarkdown is an Output Modifier for MODX
[[*content:TinymceWrapperMarkdown=`markdown`]]
or `markdownE` or `parsedown`
default =`parsedownE`
parsedownE or markdownE is good for Markdown mixed with HTML, and Markdown enclosed in HTML markdown="1"*/
if($input){
    $content = $input;
    // Parse MODX tags first || parse BBCodes here too
    $content = $modx->newObject(\'modChunk\')->process(null, $content);
    $options = explode(\',\',$options);
    if($options[0] == "markdown"){
        if (!class_exists(\'\\Michelf\\Markdown\')) {
            require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/Michelf/Markdown.inc.php\';
        }
        $content = \\Michelf\\Markdown::defaultTransform($content);
    }
    elseif($options[0] == "markdownE"){
        if (!class_exists(\'\\Michelf\\MarkdownExtra\')) {
            require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/Michelf/MarkdownExtra.inc.php\';
        }
        $content = \\Michelf\\MarkdownExtra::defaultTransform($content);
    }
    elseif($options[0] == "parsedown"){
        require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/parsedown/Parsedown.php\';
        $Parsedown = new Parsedown();
        $content = $Parsedown->text($content);
    }
    else{ //default state
        $options[0] = "parsedownExtra";
        require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/parsedown/Parsedown.php\';
        require MODX_ASSETS_PATH . \'components/tinymcewrapper/markdown/parsedown/ParsedownExtra.php\';
        $ParsedownExtra = new ParsedownExtra();
        $content = $ParsedownExtra->text($content);
    }
    if(isset($options[1])){
        $squareBracketSeparator = substr($options[1], 0, 1);
        $content = str_replace(\'[\'.$squareBracketSeparator.\'[\', \'&#91;&#91;\', $content); // do some more find/replace
    }
    return $content);
}',
    ),
  ),
);