<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "att_loginfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$att_log_delete = NULL; // Initialize page object first

class catt_log_delete extends catt_log {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'att_log';

	// Page object name
	var $PageObjName = 'att_log_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (att_log)
		if (!isset($GLOBALS["att_log"]) || get_class($GLOBALS["att_log"]) == "catt_log") {
			$GLOBALS["att_log"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["att_log"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'att_log', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("att_loglist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->sn->SetVisibility();
		$this->scan_date->SetVisibility();
		$this->pin->SetVisibility();
		$this->verifymode->SetVisibility();
		$this->inoutmode->SetVisibility();
		$this->reserved->SetVisibility();
		$this->work_code->SetVisibility();
		$this->att_id->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $att_log;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($att_log);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("att_loglist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in att_log class, att_loginfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("att_loglist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->sn->setDbValue($rs->fields('sn'));
		$this->scan_date->setDbValue($rs->fields('scan_date'));
		$this->pin->setDbValue($rs->fields('pin'));
		$this->verifymode->setDbValue($rs->fields('verifymode'));
		$this->inoutmode->setDbValue($rs->fields('inoutmode'));
		$this->reserved->setDbValue($rs->fields('reserved'));
		$this->work_code->setDbValue($rs->fields('work_code'));
		$this->att_id->setDbValue($rs->fields('att_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->scan_date->DbValue = $row['scan_date'];
		$this->pin->DbValue = $row['pin'];
		$this->verifymode->DbValue = $row['verifymode'];
		$this->inoutmode->DbValue = $row['inoutmode'];
		$this->reserved->DbValue = $row['reserved'];
		$this->work_code->DbValue = $row['work_code'];
		$this->att_id->DbValue = $row['att_id'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// sn
		// scan_date
		// pin
		// verifymode
		// inoutmode
		// reserved
		// work_code
		// att_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// scan_date
		$this->scan_date->ViewValue = $this->scan_date->CurrentValue;
		$this->scan_date->ViewValue = ew_FormatDateTime($this->scan_date->ViewValue, 0);
		$this->scan_date->ViewCustomAttributes = "";

		// pin
		$this->pin->ViewValue = $this->pin->CurrentValue;
		$this->pin->ViewCustomAttributes = "";

		// verifymode
		$this->verifymode->ViewValue = $this->verifymode->CurrentValue;
		$this->verifymode->ViewCustomAttributes = "";

		// inoutmode
		$this->inoutmode->ViewValue = $this->inoutmode->CurrentValue;
		$this->inoutmode->ViewCustomAttributes = "";

		// reserved
		$this->reserved->ViewValue = $this->reserved->CurrentValue;
		$this->reserved->ViewCustomAttributes = "";

		// work_code
		$this->work_code->ViewValue = $this->work_code->CurrentValue;
		$this->work_code->ViewCustomAttributes = "";

		// att_id
		$this->att_id->ViewValue = $this->att_id->CurrentValue;
		$this->att_id->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// scan_date
			$this->scan_date->LinkCustomAttributes = "";
			$this->scan_date->HrefValue = "";
			$this->scan_date->TooltipValue = "";

			// pin
			$this->pin->LinkCustomAttributes = "";
			$this->pin->HrefValue = "";
			$this->pin->TooltipValue = "";

			// verifymode
			$this->verifymode->LinkCustomAttributes = "";
			$this->verifymode->HrefValue = "";
			$this->verifymode->TooltipValue = "";

			// inoutmode
			$this->inoutmode->LinkCustomAttributes = "";
			$this->inoutmode->HrefValue = "";
			$this->inoutmode->TooltipValue = "";

			// reserved
			$this->reserved->LinkCustomAttributes = "";
			$this->reserved->HrefValue = "";
			$this->reserved->TooltipValue = "";

			// work_code
			$this->work_code->LinkCustomAttributes = "";
			$this->work_code->HrefValue = "";
			$this->work_code->TooltipValue = "";

			// att_id
			$this->att_id->LinkCustomAttributes = "";
			$this->att_id->HrefValue = "";
			$this->att_id->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['sn'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['scan_date'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['pin'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("att_loglist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($att_log_delete)) $att_log_delete = new catt_log_delete();

// Page init
$att_log_delete->Page_Init();

// Page main
$att_log_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$att_log_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fatt_logdelete = new ew_Form("fatt_logdelete", "delete");

// Form_CustomValidate event
fatt_logdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fatt_logdelete.ValidateRequired = true;
<?php } else { ?>
fatt_logdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $att_log_delete->ShowPageHeader(); ?>
<?php
$att_log_delete->ShowMessage();
?>
<form name="fatt_logdelete" id="fatt_logdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($att_log_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $att_log_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="att_log">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($att_log_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $att_log->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($att_log->sn->Visible) { // sn ?>
		<th><span id="elh_att_log_sn" class="att_log_sn"><?php echo $att_log->sn->FldCaption() ?></span></th>
<?php } ?>
<?php if ($att_log->scan_date->Visible) { // scan_date ?>
		<th><span id="elh_att_log_scan_date" class="att_log_scan_date"><?php echo $att_log->scan_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($att_log->pin->Visible) { // pin ?>
		<th><span id="elh_att_log_pin" class="att_log_pin"><?php echo $att_log->pin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($att_log->verifymode->Visible) { // verifymode ?>
		<th><span id="elh_att_log_verifymode" class="att_log_verifymode"><?php echo $att_log->verifymode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($att_log->inoutmode->Visible) { // inoutmode ?>
		<th><span id="elh_att_log_inoutmode" class="att_log_inoutmode"><?php echo $att_log->inoutmode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($att_log->reserved->Visible) { // reserved ?>
		<th><span id="elh_att_log_reserved" class="att_log_reserved"><?php echo $att_log->reserved->FldCaption() ?></span></th>
<?php } ?>
<?php if ($att_log->work_code->Visible) { // work_code ?>
		<th><span id="elh_att_log_work_code" class="att_log_work_code"><?php echo $att_log->work_code->FldCaption() ?></span></th>
<?php } ?>
<?php if ($att_log->att_id->Visible) { // att_id ?>
		<th><span id="elh_att_log_att_id" class="att_log_att_id"><?php echo $att_log->att_id->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$att_log_delete->RecCnt = 0;
$i = 0;
while (!$att_log_delete->Recordset->EOF) {
	$att_log_delete->RecCnt++;
	$att_log_delete->RowCnt++;

	// Set row properties
	$att_log->ResetAttrs();
	$att_log->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$att_log_delete->LoadRowValues($att_log_delete->Recordset);

	// Render row
	$att_log_delete->RenderRow();
?>
	<tr<?php echo $att_log->RowAttributes() ?>>
<?php if ($att_log->sn->Visible) { // sn ?>
		<td<?php echo $att_log->sn->CellAttributes() ?>>
<span id="el<?php echo $att_log_delete->RowCnt ?>_att_log_sn" class="att_log_sn">
<span<?php echo $att_log->sn->ViewAttributes() ?>>
<?php echo $att_log->sn->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($att_log->scan_date->Visible) { // scan_date ?>
		<td<?php echo $att_log->scan_date->CellAttributes() ?>>
<span id="el<?php echo $att_log_delete->RowCnt ?>_att_log_scan_date" class="att_log_scan_date">
<span<?php echo $att_log->scan_date->ViewAttributes() ?>>
<?php echo $att_log->scan_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($att_log->pin->Visible) { // pin ?>
		<td<?php echo $att_log->pin->CellAttributes() ?>>
<span id="el<?php echo $att_log_delete->RowCnt ?>_att_log_pin" class="att_log_pin">
<span<?php echo $att_log->pin->ViewAttributes() ?>>
<?php echo $att_log->pin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($att_log->verifymode->Visible) { // verifymode ?>
		<td<?php echo $att_log->verifymode->CellAttributes() ?>>
<span id="el<?php echo $att_log_delete->RowCnt ?>_att_log_verifymode" class="att_log_verifymode">
<span<?php echo $att_log->verifymode->ViewAttributes() ?>>
<?php echo $att_log->verifymode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($att_log->inoutmode->Visible) { // inoutmode ?>
		<td<?php echo $att_log->inoutmode->CellAttributes() ?>>
<span id="el<?php echo $att_log_delete->RowCnt ?>_att_log_inoutmode" class="att_log_inoutmode">
<span<?php echo $att_log->inoutmode->ViewAttributes() ?>>
<?php echo $att_log->inoutmode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($att_log->reserved->Visible) { // reserved ?>
		<td<?php echo $att_log->reserved->CellAttributes() ?>>
<span id="el<?php echo $att_log_delete->RowCnt ?>_att_log_reserved" class="att_log_reserved">
<span<?php echo $att_log->reserved->ViewAttributes() ?>>
<?php echo $att_log->reserved->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($att_log->work_code->Visible) { // work_code ?>
		<td<?php echo $att_log->work_code->CellAttributes() ?>>
<span id="el<?php echo $att_log_delete->RowCnt ?>_att_log_work_code" class="att_log_work_code">
<span<?php echo $att_log->work_code->ViewAttributes() ?>>
<?php echo $att_log->work_code->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($att_log->att_id->Visible) { // att_id ?>
		<td<?php echo $att_log->att_id->CellAttributes() ?>>
<span id="el<?php echo $att_log_delete->RowCnt ?>_att_log_att_id" class="att_log_att_id">
<span<?php echo $att_log->att_id->ViewAttributes() ?>>
<?php echo $att_log->att_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$att_log_delete->Recordset->MoveNext();
}
$att_log_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $att_log_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fatt_logdelete.Init();
</script>
<?php
$att_log_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$att_log_delete->Page_Terminate();
?>
