<?php

namespace App\Models;

use App\Presenters\WebSettingPresenter;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\WebSetting
 *
 * @property int $id
 * @property int|null $city_id
 * @property int|null $state_id
 * @property int|null $country_id
 * @property string|null $about_us
 * @property string|null $vision
 * @property string|null $founder_message
 * @property string|null $terms
 * @property string|null $return_policy
 * @property string|null $privacy_policy
 * @property string|null $shipping_policy
 * @property string $company_name
 * @property string|null $gst_no
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $pincode
 * @property string|null $mobile
 * @property string|null $email
 * @property string|null $facebook_url
 * @property string|null $instagram_url
 * @property string|null $youtube_url
 * @property string|null $zoom_url
 * @property string|null $grievance_name
 * @property string|null $grievance_mobile
 * @property string|null $grievance_email
 * @property string|null $nodal_name
 * @property string|null $nodal_mobile
 * @property string|null $nodal_email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 *
 * @method static Builder|WebSetting newModelQuery()
 * @method static Builder|WebSetting newQuery()
 * @method static Builder|WebSetting query()
 * @method static Builder|WebSetting whereAboutUs($value)
 * @method static Builder|WebSetting whereAddressLine1($value)
 * @method static Builder|WebSetting whereAddressLine2($value)
 * @method static Builder|WebSetting whereCityId($value)
 * @method static Builder|WebSetting whereCompanyName($value)
 * @method static Builder|WebSetting whereCountryId($value)
 * @method static Builder|WebSetting whereCreatedAt($value)
 * @method static Builder|WebSetting whereEmail($value)
 * @method static Builder|WebSetting whereFacebookUrl($value)
 * @method static Builder|WebSetting whereFounderMessage($value)
 * @method static Builder|WebSetting whereGrievanceEmail($value)
 * @method static Builder|WebSetting whereGrievanceMobile($value)
 * @method static Builder|WebSetting whereGrievanceName($value)
 * @method static Builder|WebSetting whereGstNo($value)
 * @method static Builder|WebSetting whereId($value)
 * @method static Builder|WebSetting whereInstagramUrl($value)
 * @method static Builder|WebSetting whereMobile($value)
 * @method static Builder|WebSetting whereNodalEmail($value)
 * @method static Builder|WebSetting whereNodalMobile($value)
 * @method static Builder|WebSetting whereNodalName($value)
 * @method static Builder|WebSetting wherePincode($value)
 * @method static Builder|WebSetting wherePrivacyPolicy($value)
 * @method static Builder|WebSetting whereReturnPolicy($value)
 * @method static Builder|WebSetting whereShippingPolicy($value)
 * @method static Builder|WebSetting whereStateId($value)
 * @method static Builder|WebSetting whereTerms($value)
 * @method static Builder|WebSetting whereUpdatedAt($value)
 * @method static Builder|WebSetting whereVision($value)
 * @method static Builder|WebSetting whereYoutubeUrl($value)
 * @method static Builder|WebSetting whereZoomUrl($value)
 *
 * @mixin Eloquent
 */
class WebSetting extends Model implements HasMedia
{
    use InteractsWithMedia, PresentableTrait;

    const MC_LOGO = 'logo';

    const MC_FAVICON = 'favicon';

    const MC_ADMIN_BACKGROUND = 'Admin-Background';

    const MC_MEMBER_BACKGROUND = 'Member-Background';

    protected $guarded = [];

    protected string $presenter = WebSettingPresenter::class;
}
