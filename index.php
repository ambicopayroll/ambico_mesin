<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Page object name
	var $PageObjName = 'default';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'default', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

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

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;

		// If session expired, show session expired message
		if (@$_GET["expired"] == "1")
			$this->setFailureMessage($Language->Phrase("SessionExpired"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadUserLevel(); // Load User Level
		if ($Security->AllowList(CurrentProjectID() . 'att_log'))
		$this->Page_Terminate("att_loglist.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'cuti_normatif'))
			$this->Page_Terminate("cuti_normatiflist.php");
		if ($Security->AllowList(CurrentProjectID() . 'dev_type'))
			$this->Page_Terminate("dev_typelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'device'))
			$this->Page_Terminate("devicelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ganti_jdw_d'))
			$this->Page_Terminate("ganti_jdw_dlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ganti_jdw_jk'))
			$this->Page_Terminate("ganti_jdw_jklist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ganti_jdw_pegawai'))
			$this->Page_Terminate("ganti_jdw_pegawailist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ganti_jdw_pembagian'))
			$this->Page_Terminate("ganti_jdw_pembagianlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ganti_jk'))
			$this->Page_Terminate("ganti_jklist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ganti_jk_d'))
			$this->Page_Terminate("ganti_jk_dlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ganti_jk_pegawai'))
			$this->Page_Terminate("ganti_jk_pegawailist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ganti_jk_pembagian'))
			$this->Page_Terminate("ganti_jk_pembagianlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grp_user_d'))
			$this->Page_Terminate("grp_user_dlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grp_user_m'))
			$this->Page_Terminate("grp_user_mlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'index_ot'))
			$this->Page_Terminate("index_otlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'index_type'))
			$this->Page_Terminate("index_typelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'izin'))
			$this->Page_Terminate("izinlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'jam_kerja'))
			$this->Page_Terminate("jam_kerjalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'jam_kerja_extra'))
			$this->Page_Terminate("jam_kerja_extralist.php");
		if ($Security->AllowList(CurrentProjectID() . 'jatah_cuti'))
			$this->Page_Terminate("jatah_cutilist.php");
		if ($Security->AllowList(CurrentProjectID() . 'jdw_kerja_d'))
			$this->Page_Terminate("jdw_kerja_dlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'jdw_kerja_m'))
			$this->Page_Terminate("jdw_kerja_mlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'jdw_kerja_pegawai'))
			$this->Page_Terminate("jdw_kerja_pegawailist.php");
		if ($Security->AllowList(CurrentProjectID() . 'jns_izin'))
			$this->Page_Terminate("jns_izinlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'kategori_izin'))
			$this->Page_Terminate("kategori_izinlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'kontrak_kerja'))
			$this->Page_Terminate("kontrak_kerjalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'lembur'))
			$this->Page_Terminate("lemburlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'libur'))
			$this->Page_Terminate("liburlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'pegawai'))
			$this->Page_Terminate("pegawailist.php");
		if ($Security->AllowList(CurrentProjectID() . 'pegawai_d'))
			$this->Page_Terminate("pegawai_dlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'pembagian1'))
			$this->Page_Terminate("pembagian1list.php");
		if ($Security->AllowList(CurrentProjectID() . 'pembagian2'))
			$this->Page_Terminate("pembagian2list.php");
		if ($Security->AllowList(CurrentProjectID() . 'pembagian3'))
			$this->Page_Terminate("pembagian3list.php");
		if ($Security->AllowList(CurrentProjectID() . 'pendidikan'))
			$this->Page_Terminate("pendidikanlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'setting'))
			$this->Page_Terminate("settinglist.php");
		if ($Security->AllowList(CurrentProjectID() . 'shift_result'))
			$this->Page_Terminate("shift_resultlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'sms_group'))
			$this->Page_Terminate("sms_grouplist.php");
		if ($Security->AllowList(CurrentProjectID() . 'sms_group_member'))
			$this->Page_Terminate("sms_group_memberlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'sms_recipient'))
			$this->Page_Terminate("sms_recipientlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'temp_pegawai'))
			$this->Page_Terminate("temp_pegawailist.php");
		if ($Security->AllowList(CurrentProjectID() . 'tmp'))
			$this->Page_Terminate("tmplist.php");
		if ($Security->AllowList(CurrentProjectID() . 'tmp_uareu'))
			$this->Page_Terminate("tmp_uareulist.php");
		if ($Security->AllowList(CurrentProjectID() . 'tukar_jam'))
			$this->Page_Terminate("tukar_jamlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'uareu_device'))
			$this->Page_Terminate("uareu_devicelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'user_log'))
			$this->Page_Terminate("user_loglist.php");
		if ($Security->AllowList(CurrentProjectID() . 'user_login'))
			$this->Page_Terminate("user_loginlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'versi_db'))
			$this->Page_Terminate("versi_dblist.php");
		if ($Security->AllowList(CurrentProjectID() . 'z_pay_com'))
			$this->Page_Terminate("z_pay_comlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'z_pay_grp'))
			$this->Page_Terminate("z_pay_grplist.php");
		if ($Security->AllowList(CurrentProjectID() . 'z_pay_grp_com'))
			$this->Page_Terminate("z_pay_grp_comlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'z_pay_grp_emp'))
			$this->Page_Terminate("z_pay_grp_emplist.php");
		if ($Security->AllowList(CurrentProjectID() . 'z_pay_money'))
			$this->Page_Terminate("z_pay_moneylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'z_pay_process_d'))
			$this->Page_Terminate("z_pay_process_dlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'z_pay_process_m'))
			$this->Page_Terminate("z_pay_process_mlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'z_pay_process_sd'))
			$this->Page_Terminate("z_pay_process_sdlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'z_pay_report'))
			$this->Page_Terminate("z_pay_reportlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'zx_bayar_kredit'))
			$this->Page_Terminate("zx_bayar_kreditlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'zx_jns_krd'))
			$this->Page_Terminate("zx_jns_krdlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'zx_kredit_d'))
			$this->Page_Terminate("zx_kredit_dlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'zx_kredit_m'))
			$this->Page_Terminate("zx_kredit_mlist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_user'))
			$this->Page_Terminate("t_userlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'audittrail'))
			$this->Page_Terminate("audittraillist.php");
		if ($Security->AllowList(CurrentProjectID() . 'dashboard.php'))
			$this->Page_Terminate("dashboard.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage(ew_DeniedMsg() . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
