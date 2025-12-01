import { useState } from 'react';
import { Input, Button, Loader, toast } from '@bsf/force-ui';
import { __ } from '@wordpress/i18n';

import FieldWrapper from '@Components/common/FieldWrapper';
import { doApiFetch } from '@Store';

const TestSms = ( { title, description } ) => {
	const [ phone, setPhone ] = useState( '' );
	const [ isSending, setIsSending ] = useState( false );
	const validateFields = () => {
		if ( ! phone ) {
			toast.error(
				__(
					'Please enter a phone number',
					'woo-cart-abandonment-recovery'
				)
			);
			return false;
		}
		if ( ! /^\+[1-9]\d{1,14}$/.test( phone ) ) {
			toast.error(
				__(
					'Please enter a valid phone number',
					'woo-cart-abandonment-recovery'
				)
			);
			return false;
		}
		return true;
	};

	const handleClick = () => {
		if ( ! validateFields() ) {
			return;
		}
		const ajaxUrl = cart_abandonment_admin?.ajax_url;
		const nonce = cart_abandonment_admin?.send_test_sms_nonce;

		const formData = new FormData();
		formData.append( 'action', 'wcar_pro_send_test_sms' );
		formData.append( 'phone_number', phone );
		formData.append( 'security', nonce );

		setIsSending( true );
		doApiFetch(
			ajaxUrl,
			formData,
			'POST',
			( response ) => {
				if ( response.success ) {
					toast.success(
						__(
							'Test SMS sent successfully!',
							'woo-cart-abandonment-recovery'
						)
					);
				} else {
					toast.error(
						__(
							'Failed to send test SMS',
							'woo-cart-abandonment-recovery'
						),
						{
							description: response.data?.message || '',
						}
					);
				}
				setIsSending( false );
			},
			( error ) => {
				toast.error(
					__(
						'Failed to send test SMS',
						'woo-cart-abandonment-recovery'
					),
					{
						description: error.data?.message || '',
					}
				);
				setIsSending( false );
			},
			true
		);
	};

	return (
		<FieldWrapper title={ title } description={ description } type="block">
			<div className="flex gap-2 items-center">
				<div className="flex-1">
					<Input
						className="w-full focus:[&>input]:ring-focus"
						type="text"
						size="md"
						value={ phone }
						onChange={ setPhone }
						placeholder={ __(
							'Enter Phone no.',
							'woo-cart-abandonment-recovery'
						) }
					/>
				</div>
				<Button
					className="w-fit bg-primary-25 text-primary-600 outline-primary-300 hover:bg-primary-25 hover:outline-primary-300"
					size="md"
					tag="button"
					variant="outline"
					icon={
						isSending && (
							<Loader
								className="text-primary-600"
								size="md"
								variant="primary"
							/>
						)
					}
					iconPosition="left"
					onClick={ handleClick }
					disabled={ isSending }
				>
					{ __( 'Send a Test SMS', 'woo-cart-abandonment-recovery' ) }
				</Button>
			</div>
		</FieldWrapper>
	);
};

export default TestSms;
