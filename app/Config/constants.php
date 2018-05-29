<?php
/**
 * Book or Rent
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    BookorRent
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class ConstCommsisionType
{
    const Amount = 'amount';
    const Percentage = 'percentage';
}
class ConstUserTypes
{
    const Admin = 1;
    const User = 2;
}
class ConstStreetView
{
    const HideStreetView = 1;
    const CloseToMyAddress = 2;
    const NearBy = 3;
}
class ConstStreetAction
{
    const Hidestreetview = 1;
    const Closesttoaddress = 2;
    const Nearby = 3;
}
class ConstMeasureAction
{
    const Squarefeet = 1;
    const Squaremeasures = 2;
}
class ConstAttachment
{
    const UserAvatar = 1;
    const Item = 3;
	const Processing = 86;
}
class ConstFriendRequestStatus
{
    const Pending = 1;
    const Approved = 2;
    const Reject = 3;
}
class ConstMessageFolder
{
    const Inbox = 1;
    const SentMail = 2;
    const Drafts = 3;
    const Spam = 4;
    const Trash = 5;
}
// Setting for privacy settings
class ConstPrivacySetting
{
    const EveryOne = 1;
    const Users = 2;
    const Friends = 3;
    const Nobody = 4;
}
class ConstProjectStatus
{
    const Confirmed = 18;
    const Rejected = 6;
    const Available = 16;
    const NotAvailable = 17;
    const Pending = 2;
    const Waiting = 1;
    const Completed = 4;
    const Canceled = 5;
}
class ConstMoreAction
{
    const Inactive = 1;
    const Active = 2;
    const Delete = 3;
    const OpenID = 4;
    const Export = 5;
    const Normal = 38;
    const IsReplied = 23;
    const Unsatisfy = 11;
    const Satisfy = 10;
    const UserFlagged = 37;
    const NotifiedInactiveUsers = 30;
    const Approved = 6;
    const Disapproved = 7;
    const Featured = 8;
    const Notfeatured = 9;
    const Unflagged = 14;
    const Facebook = 23;
    const Twitter = 22;
    const Gmail = 39;
    const Yahoo = 40;
	const LinkedIn = 41;
	const GooglePlus = 42;
    const Flagged = 13;
    const Unsuspend = 15;
    const Suspend = 10;
    const Negotiable = 15;
    const WaitingforAcceptance = 1;
    const InProgress = 2;
    const Completed = 4;
    const Canceled = 5;
    const Rejected = 6;
    const PaymentCleared = 7;
    const HomePage = 41;
    const Verified = 42;
    const WaitingForVerification = 43;
	const Collection = 44;
	const Imported = 45;
	const TestMode = 46;
	const MassPay = 47;
	const ItemListing = 48;
	const ItemVerification = 49;
	const AddWallet = 50;
	const ItemBooking = 51;
	const SignupFee = 52;
	const Prelaunch = 53;
	const PrivateBeta = 54;
	const PrelaunchSubscribed = 55;
	const PrivateBetaSubscribed = 56;	
	const Subscribed = 57;
	const Unsubscribed = 58;
}
// Banned ips types
class ConstBannedTypes
{
    const SingleIPOrHostName = 1;
    const IPRange = 2;
    const RefererBlock = 3;
}
// Banned ips durations
class ConstBannedDurations
{
    const Permanent = 1;
    const Days = 2;
    const Weeks = 3;
}
//payment related class constant
class ConstPaymentGateways
{
    const SudoPay = 1;
    const Wallet = 2;
	// mass payment manual
	const ManualPay = 5;
}
class ConstPaymentGatewaysName
{
    const SudoPay = 'ZazPay';
    const Wallet = 'Wallet';
}
class ConstUserIds
{
    const Admin = 1;
}
class ConstItemUserType
{
    const Booker = 1;
    const Host = 2;
}
class ConstPaymentGatewayFilterOptions
{
    const Active = 1;
    const Inactive = 2;
    const TestMode = 3;
    const LiveMode = 4;
}
class ConstPaymentGatewayMoreActions
{
    const Activate = 1;
    const Deactivate = 2;
    const MakeTestMode = 3;
    const MakeLiveMode = 4;
    const Delete = 5;
}
class ConstTransactionTypes
{
	const SignupFee = 1;
    const AddedToWallet = 2;
	const ItemListingFee = 3;
    const BookItem = 4;
    const RefundForExpiredBooking = 5;
    const RefundForRejectedBooking = 6;
    const RefundForCanceledBooking = 7;
    const RefundForBookingCanceledByAdmin = 8;
    const HostAmountCleared = 9;
    const CashWithdrawalRequest = 10;
    const CashWithdrawalRequestApproved = 11;
    const CashWithdrawalRequestRejected = 12;
    const CashWithdrawalRequestPaid = 13;
    const CashWithdrawalRequestFailed = 14;
    const AffiliateCashWithdrawalRequest = 15;
    const AffliateCashWithdrawalRequestRejected = 16;
    const AffliateCashWithdrawalRequestPaid = 17;
    const AffliateCashWithdrawalRequestFailed = 18;
    const AffliateCashWithdrawalRequestApproved = 19;
	const AdminAddFundToWallet = 20;
    const AdminDeductFundFromWallet = 21;
}
class ConstItemUserStatus
{
    const BookingRequest = 1;
	const BookingRequestConfirmed = 2;
	const PaymentPending = 3;
    const WaitingforAcceptance = 4;
    const Rejected = 5;
    const Canceled = 6;
	const CanceledByAdmin = 7;
	const Expired = 8;
	const Confirmed = 9;
    const WaitingforReview = 10;
    const BookerReviewed = 11;
    const HostReviewed = 12;
    const Completed = 13;
	const BookerConversation = 14;
	const PrivateConversation = 15;
	const BookingRequestConversation = 16;
	const SenderNotification = 17;
	const BookingRequestRejected = 18;
}
class ConstCancellationPolicyRefundDay
// Values in days //

{
    const Flexible = 1;
    const Moderate = 5;
    const Strict = 7;
}
class ConstItemStatus
{
    const NotAvailable = 0;
    const Available = 1;
}
class ConstItemUser
{
    const Paid = 1;
    const notpaid = 0;
}
class ConstPaymentGatewayFlow
{
    const BookerSiteHost = 'Booker -> Site -> Host';
    const BookerHostSite = 'Booker -> Host -> Site';
}
class ConstPaymentGatewayFee
{
    const Host = 'Host';
    const Site = 'Site';
    const SiteAndHost = 'Site and Host';
}
class ConstViewType
{
    const NormalView = 1;
    const EmbedView = 2;
}
class ConstModule
{
    const Affiliate = 14;
    const Friends = 12;
}
class ConstModuleEnableFields
{
    const Affiliate = 160;
    const Friends = 253;
}
class ConstPaymentType
{
    const SignupFee = 1;
	const AddAmountToWallet = 2;
	const ItemListingFee = 3;
	const ItemVerifyFee = 4;
	const BookingAmount = 5;
	const CancelBookingAmount = 6;
	const BookerFeeToSite = 7;
}
class ConstSiteState
{
    const Prelaunch = 1;
	const PrivateBeta = 2;
	const Launched = 3;
}
class ConstUserAvatarSource
{
    const Attachment = 1;
	const Facebook = 2;
	const Twitter = 3;
	const Google = 4;
	const Linkedin = 5;
	const GooglePlus = 6;
}
class ConstCustomSource
{
	const Hour = 1;
	const Day = 2;
	const Week = 3;
	const Month = 4;
}
class ConstSeatStatus
{
	const Available = 1;
	const Unavailable = 2;
	const Blocked = 3;
	const Booked = 4;
	const NoSeat = 5;
	const WaitingForAcceptance = 6;
}
class ConstSeatNameType
{
	const Alphabet = 1;
	const RomanNumbers = 2;
	const Number = 3;
}
class ConstSeatArrangementDirection
{
	const LeftToRight = 1;
	const RightToLeft = 2;
}
class ConstStagePosition
{
	const Top = 1;
	const Bottom = 2;
}
// @todo "Auto review"
// @todo "Ask Question"
?>