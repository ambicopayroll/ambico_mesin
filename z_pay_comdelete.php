<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "z_pay_cominfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$z_pay_com_delete = NULL; // Initialize page object first

class cz_pay_com_delete extends cz_pay_com {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'z_pay_com';

	// Page object name
	var $PageObjName = 'z_pay_com_delete';

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

		// Table object (z_pay_com)
		if (!isset($GLOBALS["z_pay_com"]) || get_class($GLOBALS["z_pay_com"]) == "cz_pay_com") {
			$GLOBALS["z_pay_com"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["z_pay_com"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'z_pay_com', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("z_pay_comlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->com_id->SetVisibility();
		$this->com_kode->SetVisibility();
		$this->com_name->SetVisibility();
		$this->type_com->SetVisibility();
		$this->fluctuate->SetVisibility();
		$this->no_urut->SetVisibility();
		$this->param->SetVisibility();
		$this->hitung->SetVisibility();
		$this->dibayar->SetVisibility();
		$this->cara_bayar->SetVisibility();
		$this->pinjaman->SetVisibility();
		$this->lastupdate_date->SetVisibility();
		$this->lastupdate_user->SetVisibility();

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
		global $EW_EXPORT, $z_pay_com;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($z_pay_com);
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
			$this->Page_Terminate("z_pay_comlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in z_pay_com class, z_pay_cominfo.php

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
				$this->Page_Terminate("z_pay_comlist.php"); // Return to list
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
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->com_kode->setDbValue($rs->fields('com_kode'));
		$this->com_name->setDbValue($rs->fields('com_name'));
		$this->type_com->setDbValue($rs->fields('type_com'));
		$this->fluctuate->setDbValue($rs->fields('fluctuate'));
		$this->no_urut->setDbValue($rs->fields('no_urut'));
		$this->param->setDbValue($rs->fields('param'));
		$this->hitung->setDbValue($rs->fields('hitung'));
		$this->dibayar->setDbValue($rs->fields('dibayar'));
		$this->cara_bayar->setDbValue($rs->fields('cara_bayar'));
		$this->pinjaman->setDbValue($rs->fields('pinjaman'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->com_id->DbValue = $row['com_id'];
		$this->com_kode->DbValue = $row['com_kode'];
		$this->com_name->DbValue = $row['com_name'];
		$this->type_com->DbValue = $row['type_com'];
		$this->fluctuate->DbValue = $row['fluctuate'];
		$this->no_urut->DbValue = $row['no_urut'];
		$this->param->DbValue = $row['param'];
		$this->hitung->DbValue = $row['hitung'];
		$this->dibayar->DbValue = $row['dibayar'];
		$this->cara_bayar->DbValue = $row['cara_bayar'];
		$this->pinjaman->DbValue = $row['pinjaman'];
		$this->lastupdate_date->DbValue = $row['lastupdate_date'];
		$this->lastupdate_user->DbValue = $row['lastupdate_user'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// com_id
		// com_kode
		// com_name
		// type_com
		// fluctuate
		// no_urut
		// param
		// hitung
		// dibayar
		// cara_bayar
		// pinjaman
		// lastupdate_date
		// lastupdate_user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// com_id
		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// com_kode
		$this->com_kode->ViewValue = $this->com_kode->CurrentValue;
		$this->com_kode->ViewCustomAttributes = "";

		// com_name
		$this->com_name->ViewValue = $this->com_name->CurrentValue;
		$this->com_name->ViewCustomAttributes = "";

		// type_com
		$this->type_com->ViewValue = $this->type_com->CurrentValue;
		$this->type_com->ViewCustomAttributes = "";

		// fluctuate
		$this->fluctuate->ViewValue = $this->fluctuate->CurrentValue;
		$this->fluctuate->ViewCustomAttributes = "";

		// no_urut
		$this->no_urut->ViewValue = $this->no_urut->CurrentValue;
		$this->no_urut->ViewCustomAttributes = "";

		// param
		$this->param->ViewValue = $this->param->CurrentValue;
		$this->param->ViewCustomAttributes = "";

		// hitung
		$this->hitung->ViewValue = $this->hitung->CurrentValue;
		$this->hitung->ViewCustomAttributes = "";

		// dibayar
		$this->dibayar->ViewValue = $this->dibayar->CurrentValue;
		$this->dibayar->ViewCustomAttributes = "";

		// cara_bayar
		$this->cara_bayar->ViewValue = $this->cara_bayar->CurrentValue;
		$this->cara_bayar->ViewCustomAttributes = "";

		// pinjaman
		$this->pinjaman->ViewValue = $this->pinjaman->CurrentValue;
		$this->pinjaman->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";
			$this->com_id->TooltipValue = "";

			// com_kode
			$this->com_kode->LinkCustomAttributes = "";
			$this->com_kode->HrefValue = "";
			$this->com_kode->TooltipValue = "";

			// com_name
			$this->com_name->LinkCustomAttributes = "";
			$this->com_name->HrefValue = "";
			$this->com_name->TooltipValue = "";

			// type_com
			$this->type_com->LinkCustomAttributes = "";
			$this->type_com->HrefValue = "";
			$this->type_com->TooltipValue = "";

			// fluctuate
			$this->fluctuate->LinkCustomAttributes = "";
			$this->fluctuate->HrefValue = "";
			$this->fluctuate->TooltipValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";
			$this->no_urut->TooltipValue = "";

			// param
			$this->param->LinkCustomAttributes = "";
			$this->param->HrefValue = "";
			$this->param->TooltipValue = "";

			// hitung
			$this->hitung->LinkCustomAttributes = "";
			$this->hitung->HrefValue = "";
			$this->hitung->TooltipValue = "";

			// dibayar
			$this->dibayar->LinkCustomAttributes = "";
			$this->dibayar->HrefValue = "";
			$this->dibayar->TooltipValue = "";

			// cara_bayar
			$this->cara_bayar->LinkCustomAttributes = "";
			$this->cara_bayar->HrefValue = "";
			$this->cara_bayar->TooltipValue = "";

			// pinjaman
			$this->pinjaman->LinkCustomAttributes = "";
			$this->pinjaman->HrefValue = "";
			$this->pinjaman->TooltipValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";
			$this->lastupdate_date->TooltipValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
			$this->lastupdate_user->TooltipValue = "";
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
				$sThisKey .= $row['com_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("z_pay_comlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($z_pay_com_delete)) $z_pay_com_delete = new cz_pay_com_delete();

// Page init
$z_pay_com_delete->Page_Init();

// Page main
$z_pay_com_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$z_pay_com_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fz_pay_comdelete = new ew_Form("fz_pay_comdelete", "delete");

// Form_CustomValidate event
fz_pay_comdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fz_pay_comdelete.ValidateRequired = true;
<?php } else { ?>
fz_pay_comdelete.ValidateRequired = false; 
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
<?php $z_pay_com_delete->ShowPageHeader(); ?>
<?php
$z_pay_com_delete->ShowMessage();
?>
<form name="fz_pay_comdelete" id="fz_pay_comdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($z_pay_com_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $z_pay_com_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="z_pay_com">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($z_pay_com_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $z_pay_com->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($z_pay_com->com_id->Visible) { // com_id ?>
		<th><span id="elh_z_pay_com_com_id" class="z_pay_com_com_id"><?php echo $z_pay_com->com_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->com_kode->Visible) { // com_kode ?>
		<th><span id="elh_z_pay_com_com_kode" class="z_pay_com_com_kode"><?php echo $z_pay_com->com_kode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->com_name->Visible) { // com_name ?>
		<th><span id="elh_z_pay_com_com_name" class="z_pay_com_com_name"><?php echo $z_pay_com->com_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->type_com->Visible) { // type_com ?>
		<th><span id="elh_z_pay_com_type_com" class="z_pay_com_type_com"><?php echo $z_pay_com->type_com->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->fluctuate->Visible) { // fluctuate ?>
		<th><span id="elh_z_pay_com_fluctuate" class="z_pay_com_fluctuate"><?php echo $z_pay_com->fluctuate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->no_urut->Visible) { // no_urut ?>
		<th><span id="elh_z_pay_com_no_urut" class="z_pay_com_no_urut"><?php echo $z_pay_com->no_urut->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->param->Visible) { // param ?>
		<th><span id="elh_z_pay_com_param" class="z_pay_com_param"><?php echo $z_pay_com->param->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->hitung->Visible) { // hitung ?>
		<th><span id="elh_z_pay_com_hitung" class="z_pay_com_hitung"><?php echo $z_pay_com->hitung->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->dibayar->Visible) { // dibayar ?>
		<th><span id="elh_z_pay_com_dibayar" class="z_pay_com_dibayar"><?php echo $z_pay_com->dibayar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->cara_bayar->Visible) { // cara_bayar ?>
		<th><span id="elh_z_pay_com_cara_bayar" class="z_pay_com_cara_bayar"><?php echo $z_pay_com->cara_bayar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->pinjaman->Visible) { // pinjaman ?>
		<th><span id="elh_z_pay_com_pinjaman" class="z_pay_com_pinjaman"><?php echo $z_pay_com->pinjaman->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->lastupdate_date->Visible) { // lastupdate_date ?>
		<th><span id="elh_z_pay_com_lastupdate_date" class="z_pay_com_lastupdate_date"><?php echo $z_pay_com->lastupdate_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_com->lastupdate_user->Visible) { // lastupdate_user ?>
		<th><span id="elh_z_pay_com_lastupdate_user" class="z_pay_com_lastupdate_user"><?php echo $z_pay_com->lastupdate_user->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$z_pay_com_delete->RecCnt = 0;
$i = 0;
while (!$z_pay_com_delete->Recordset->EOF) {
	$z_pay_com_delete->RecCnt++;
	$z_pay_com_delete->RowCnt++;

	// Set row properties
	$z_pay_com->ResetAttrs();
	$z_pay_com->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$z_pay_com_delete->LoadRowValues($z_pay_com_delete->Recordset);

	// Render row
	$z_pay_com_delete->RenderRow();
?>
	<tr<?php echo $z_pay_com->RowAttributes() ?>>
<?php if ($z_pay_com->com_id->Visible) { // com_id ?>
		<td<?php echo $z_pay_com->com_id->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_com_id" class="z_pay_com_com_id">
<span<?php echo $z_pay_com->com_id->ViewAttributes() ?>>
<?php echo $z_pay_com->com_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->com_kode->Visible) { // com_kode ?>
		<td<?php echo $z_pay_com->com_kode->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_com_kode" class="z_pay_com_com_kode">
<span<?php echo $z_pay_com->com_kode->ViewAttributes() ?>>
<?php echo $z_pay_com->com_kode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->com_name->Visible) { // com_name ?>
		<td<?php echo $z_pay_com->com_name->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_com_name" class="z_pay_com_com_name">
<span<?php echo $z_pay_com->com_name->ViewAttributes() ?>>
<?php echo $z_pay_com->com_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->type_com->Visible) { // type_com ?>
		<td<?php echo $z_pay_com->type_com->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_type_com" class="z_pay_com_type_com">
<span<?php echo $z_pay_com->type_com->ViewAttributes() ?>>
<?php echo $z_pay_com->type_com->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->fluctuate->Visible) { // fluctuate ?>
		<td<?php echo $z_pay_com->fluctuate->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_fluctuate" class="z_pay_com_fluctuate">
<span<?php echo $z_pay_com->fluctuate->ViewAttributes() ?>>
<?php echo $z_pay_com->fluctuate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->no_urut->Visible) { // no_urut ?>
		<td<?php echo $z_pay_com->no_urut->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_no_urut" class="z_pay_com_no_urut">
<span<?php echo $z_pay_com->no_urut->ViewAttributes() ?>>
<?php echo $z_pay_com->no_urut->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->param->Visible) { // param ?>
		<td<?php echo $z_pay_com->param->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_param" class="z_pay_com_param">
<span<?php echo $z_pay_com->param->ViewAttributes() ?>>
<?php echo $z_pay_com->param->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->hitung->Visible) { // hitung ?>
		<td<?php echo $z_pay_com->hitung->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_hitung" class="z_pay_com_hitung">
<span<?php echo $z_pay_com->hitung->ViewAttributes() ?>>
<?php echo $z_pay_com->hitung->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->dibayar->Visible) { // dibayar ?>
		<td<?php echo $z_pay_com->dibayar->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_dibayar" class="z_pay_com_dibayar">
<span<?php echo $z_pay_com->dibayar->ViewAttributes() ?>>
<?php echo $z_pay_com->dibayar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->cara_bayar->Visible) { // cara_bayar ?>
		<td<?php echo $z_pay_com->cara_bayar->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_cara_bayar" class="z_pay_com_cara_bayar">
<span<?php echo $z_pay_com->cara_bayar->ViewAttributes() ?>>
<?php echo $z_pay_com->cara_bayar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->pinjaman->Visible) { // pinjaman ?>
		<td<?php echo $z_pay_com->pinjaman->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_pinjaman" class="z_pay_com_pinjaman">
<span<?php echo $z_pay_com->pinjaman->ViewAttributes() ?>>
<?php echo $z_pay_com->pinjaman->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->lastupdate_date->Visible) { // lastupdate_date ?>
		<td<?php echo $z_pay_com->lastupdate_date->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_lastupdate_date" class="z_pay_com_lastupdate_date">
<span<?php echo $z_pay_com->lastupdate_date->ViewAttributes() ?>>
<?php echo $z_pay_com->lastupdate_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_com->lastupdate_user->Visible) { // lastupdate_user ?>
		<td<?php echo $z_pay_com->lastupdate_user->CellAttributes() ?>>
<span id="el<?php echo $z_pay_com_delete->RowCnt ?>_z_pay_com_lastupdate_user" class="z_pay_com_lastupdate_user">
<span<?php echo $z_pay_com->lastupdate_user->ViewAttributes() ?>>
<?php echo $z_pay_com->lastupdate_user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$z_pay_com_delete->Recordset->MoveNext();
}
$z_pay_com_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $z_pay_com_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fz_pay_comdelete.Init();
</script>
<?php
$z_pay_com_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$z_pay_com_delete->Page_Terminate();
?>
