import { useState, useEffect } from '@wordpress/element';
import { __, _n, sprintf } from '@wordpress/i18n';
import { useToast } from '@getdokan/dokan-ui';
import { DokanBadge, PriceHtml, DokanButton } from '@dokan/components';
import { capitalCase } from '@dokan/utilities';

const PricingCard = ( {
    pack,
    currentSubscription,
    currentPackProduct,
    hasManagePermission,
} ) => {
    const toast = useToast();
    const [ packId, setPackId ] = useState( 0 );
    const [ subscriptionId, setSubscriptionId ] = useState( 0 );
    const [ formattedFeatures, setFormattedFeatures ] = useState( [] );

    useEffect( () => {
        if ( ! pack?.id ) {
            return;
        }

        setPackId( Number( pack.id ) );
    }, [ pack ] );

    useEffect( () => {
        if ( ! currentSubscription?.subscription_id ) {
            return;
        }

        if ( ! currentSubscription?.order_id ) {
            return;
        }

        setSubscriptionId( Number( currentSubscription.subscription_id ) );
    }, [ currentSubscription ] );

    useEffect( () => {
        setFormattedFeatures( formatFeatures( pack ) );
    }, [] );

    // Format features data.
    const formatFeatures = ( item ) => {
        const features = [];

        // Format number of products
        if ( item.no_of_product === '-1' ) {
            features.push( __( 'Unlimited Products', 'dokan' ) );
        } else {
            features.push(
                // translators: %d: number of products.
                sprintf(
                    _n(
                        '%d Product',
                        '%d Products',
                        parseInt( item?.no_of_product ),
                        'dokan'
                    ),
                    item?.no_of_product
                )
            );
        }

        // Format gallery restriction
        if ( item.gallery_restriction === 'yes' ) {
            features.push(
                sprintf(
                    // translators: %d: gallery image limit.
                    _n(
                        'Gallery Limit: %d image',
                        'Gallery Limit: %d images',
                        parseInt( item?.gallery_restriction_count ),
                        'dokan'
                    ),
                    item?.gallery_restriction_count
                )
            );
        } else {
            features.push( __( 'Unlimited Gallery Images', 'dokan' ) );
        }

        // Format advertisement slots
        if ( item.advertisement_slot_count ) {
            if ( item.advertisement_slot_count === '-1' ) {
                features.push( __( 'Unlimited Advertisement Slots', 'dokan' ) );
            } else {
                features.push(
                    sprintf(
                        // translators: %d: number of advertisement slots.
                        _n(
                            '%d Advertisement Slot',
                            '%d Advertisement Slots',
                            parseInt( item?.advertisement_slot_count ),
                            'dokan'
                        ),
                        item?.advertisement_slot_count
                    )
                );
            }
        }

        // Format trial period
        if ( item.allowed_trial === 'yes' ) {
            features.push(
                sprintf(
                    // Translators: %d is the trial duration, %s is the trial period type (e.g., days, weeks, months).
                    _n(
                        '%d %s Trial',
                        '%d %ss Trial',
                        parseInt( item?.trial_period_range ),
                        'dokan'
                    ),
                    item?.trial_period_range,
                    capitalCase( item?.trial_period_types )
                )
            );
        }

        return features;
    };

    // Is active subscription.
    const isActiveSubscription = () => {
        return packId === subscriptionId;
    };

    // Prevent pack switching.
    const preventPackSwitching = ( e ) => {
        if (
            subscriptionId &&
            currentSubscription?.is_recurring &&
            ! currentSubscription?.has_active_cancelled_sub &&
            ! isActiveSubscription()
        ) {
            e.preventDefault();

            toast( {
                type: 'error',
                title: __(
                    'You are already under a recurring subscription plan. To switch pack, you need to cancel it first.',
                    'dokan'
                ),
            } );
        }
    };

    return (
        <div
            className={ `flex flex-col justify-around items-start p-6 bg-white rounded-lg shadow-sm border ${
                isActiveSubscription() ? 'border-dokan-btn' : 'border-gray-200'
            }` }
        >
            <div className="flex justify-between items-baseline mb-4 min-w-full">
                <h3 className="mr-2 text-lg font-small text-gray-700">
                    { pack.title }
                </h3>
                <div className="flex items-center gap-2">
                    <span className="text-sm">
                        <DokanBadge
                            variant={
                                pack.recurring_payment === 'yes'
                                    ? 'secondary'
                                    : 'primary'
                            }
                            label={
                                pack.recurring_payment === 'yes'
                                    ? __( 'Recurring', 'dokan' )
                                    : __( 'Non Recurring', 'dokan' )
                            }
                        />

                        { isActiveSubscription() && (
                            <DokanBadge
                                variant="success"
                                label={ __( 'Active', 'dokan' ) }
                                className="ml-1"
                            />
                        ) }
                    </span>
                </div>
            </div>

            <div className="flex justify-start items-baseline mb-6">
                <span className="text-4xl font-bold">
                    <PriceHtml price={ pack.price } />
                </span>
                <span className="ml-1 text-gray-500">
                    { pack.recurring_payment === 'yes'
                        ? `/ ${ pack.recurring_period_type }`
                        : pack.pack_validity === '0'
                        ? `/ ${ __( 'unlimited days', 'dokan' ) }`
                        : `/ ${ pack.pack_validity } ${ __(
                              'days',
                              'dokan'
                          ) }` }
                </span>
            </div>

            <ul className="mb-6 space-y-4 min-h-32">
                { formattedFeatures.map( ( feature, index ) => (
                    <li key={ index } className="flex items-start">
                        <svg
                            className="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                        <span className="ml-3 text-gray-600">{ feature }</span>
                    </li>
                ) ) }
            </ul>

            <DokanButton
                variant="primary"
                link={ ! hasManagePermission }
                onClick={ preventPackSwitching }
                disabled={ hasManagePermission }
                href={
                    isActiveSubscription()
                        ? currentPackProduct?.permalink
                        : `?add-to-cart=${ packId }`
                }
                className={ `m-0 w-full py-2 px-4 dokan-btn ${
                    isActiveSubscription() ? 'dokan-btn-secondary' : ''
                }` }
            >
                { subscriptionId && currentSubscription?.order_id
                    ? isActiveSubscription()
                        ? __( 'Your Pack', 'dokan' )
                        : __( 'Switch Pack', 'dokan' )
                    : pack.allowed_trial === 'yes'
                    ? __( 'Start Free Trial', 'dokan' )
                    : __( 'Buy Now', 'dokan' ) }
            </DokanButton>
        </div>
    );
};

export default PricingCard;
