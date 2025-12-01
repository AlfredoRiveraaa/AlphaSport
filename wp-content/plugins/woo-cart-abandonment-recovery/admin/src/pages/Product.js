/* eslint-disable no-mixed-spaces-and-tabs, indent, @wordpress/i18n-no-variables, @wordpress/i18n-no-collapsible-whitespace, @wordpress/i18n-translator-comments */
import { useState, useEffect } from 'react';
import {
	Title,
	Table,
	Pagination,
	Select,
	Input,
	Button,
	toast,
} from '@bsf/force-ui';
import {
	TrashIcon,
	MagnifyingGlassIcon,
	XMarkIcon,
	ExclamationTriangleIcon,
	EyeIcon,
} from '@heroicons/react/24/outline';
import { __, sprintf } from '@wordpress/i18n';

import SectionWrapper from '@Components/common/SectionWrapper';
import SkeletonLoader from '@Components/common/skeletons/SkeletonLoader';
import { doApiFetch, useStateValue } from '@Store';
import { EmptyBlock } from '@Components/common/empty-blocks';
import ExportToExcel from '@Components/common/ExportToExcel';
import AppTooltip from '@Components/common/AppTooltip';
import ConfirmationModal from '@Components/common/ConfirmationModal';
import { ProUpgradeCta, ProductReportDummyData } from '@Components/pro';
import { useProAccess } from '@Components/pro/useProAccess';

const Product = () => {
	const [ selected, setSelected ] = useState( [] );
	const [ currentPage, setCurrentPage ] = useState( 1 );
	const [ itemsPerPage, setItemsPerPage ] = useState( 20 );
	const [ searchText, setSearchText ] = useState( '' );
	const [ isLoading, setIsLoading ] = useState( true );
	const [ data, setData ] = useState( [] );
	const [ state, dispatch ] = useStateValue();
	const { canAccessProFeatures, shouldBlockProFeatures } = useProAccess();
	const storeProductData = state?.productData || null;

	const [ deleteModal, setDeleteModal ] = useState( {
		isOpen: false,
		type: null,
		id: null,
	} );
	const [ isDeleting, setIsDeleting ] = useState( false );
	// Single condition check - this is all you need for pro feature access!
	const canAccessPro = canAccessProFeatures();
	const isFeatureBlocked = shouldBlockProFeatures();

	useEffect( () => {
		// Only fetch data if pro features are accessible
		if ( canAccessPro ) {
			if ( storeProductData ) {
				setData( storeProductData );
				setIsLoading( false );
			} else {
				const fetchData = async () => {
					setIsLoading( true );
					await doApiFetch(
						'/wcar-pro/v1/admin/product-report',
						{},
						'POST',
						( response ) => {
							setData( response.items || [] );
							dispatch( {
								type: 'FETCH_PRODUCT_DATA_SUCCESS',
								productData: response.items || [],
							} );
							setIsLoading( false );
						},
						() => {
							setIsLoading( false );
						}
					);
				};
				fetchData();
			}
		} else {
			// Pro features not accessible - don't fetch data, show dummy data instead
			setIsLoading( false );
			setData( [] );
		}
	}, [ canAccessPro ] );

	const handleCheckboxChange = ( checked, value ) => {
		if ( checked ) {
			setSelected( [ ...selected, value.id ] );
		} else {
			setSelected( selected.filter( ( item ) => item !== value.id ) );
		}
	};

	const toggleSelectAll = ( checked ) => {
		if ( checked ) {
			setSelected( filteredData.map( ( item ) => item.id ) );
		} else {
			setSelected( [] );
		}
	};

	const handleSearch = ( value ) => {
		setSearchText( value );
		setCurrentPage( 1 );
	};

	const handleItemsPerPageChange = ( value ) => {
		setItemsPerPage( Number( value ) );
		setCurrentPage( 1 );
	};

	const handlePageChange = ( page ) => {
		setCurrentPage( page );
	};

	const filteredData = data.filter( ( item ) =>
		item.productName.toLowerCase().includes( searchText.toLowerCase() )
	);

	const indexOfLastItem = currentPage * itemsPerPage;
	const indexOfFirstItem = indexOfLastItem - itemsPerPage;
	const currentItems = filteredData.slice(
		indexOfFirstItem,
		indexOfLastItem
	);
	const totalPages = Math.ceil( filteredData.length / itemsPerPage );

	// Handle delete trigger
	const handleDeleteTrigger = () => {
		if ( selected.length === 0 ) {
			return;
		}
		setDeleteModal( { isOpen: true, type: 'bulk', id: null } );
	};

	// Handle bulk delete
	const handleBulkDelete = () => {
		setIsDeleting( true );
		const ajaxUrl = cart_abandonment_admin?.ajax_url;
		const nonce = cart_abandonment_admin?.delete_product_reports_nonce;

		const formData = new window.FormData();
		formData.append( 'action', 'wcar_delete_product_reports' );
		selected.forEach( ( id ) => formData.append( 'ids[]', id ) );
		formData.append( 'security', nonce );

		doApiFetch(
			ajaxUrl,
			formData,
			'POST',
			( response ) => {
				if ( response.success ) {
					const updated = data.filter(
						( record ) => ! selected.includes( record.id )
					);
					setData( updated );
					dispatch( {
						type: 'FETCH_PRODUCT_DATA_SUCCESS',
						productData: updated,
					} );
					setSelected( [] );
					toast.success(
						__(
							'Record(s) deleted',
							'woo-cart-abandonment-recovery'
						)
					);
				} else {
					toast.error(
						__( 'Delete failed', 'woo-cart-abandonment-recovery' ),
						{
							description: response.data?.message || '',
						}
					);
				}
				setIsDeleting( false );
				setDeleteModal( { isOpen: false, type: null, id: null } );
			},
			( error ) => {
				toast.error(
					__( 'Delete failed', 'woo-cart-abandonment-recovery' ),
					{
						description: error.data?.message || '',
					}
				);
				setIsDeleting( false );
				setDeleteModal( { isOpen: false, type: null, id: null } );
			},
			true
		);
	};

	// Handle single delete
	const handleSingleDelete = () => {
		setIsDeleting( true );
		const ajaxUrl = cart_abandonment_admin?.ajax_url;
		const nonce = cart_abandonment_admin?.delete_product_reports_nonce;

		const formData = new window.FormData();
		formData.append( 'action', 'wcar_delete_product_reports' );
		formData.append( 'ids[]', deleteModal.id );
		formData.append( 'security', nonce );

		doApiFetch(
			ajaxUrl,
			formData,
			'POST',
			( response ) => {
				if ( response.success ) {
					const updated = data.filter(
						( record ) => record.id !== deleteModal.id
					);
					setData( updated );
					dispatch( {
						type: 'FETCH_PRODUCT_DATA_SUCCESS',
						productData: updated,
					} );
					toast.success(
						__( 'Record deleted', 'woo-cart-abandonment-recovery' )
					);
				} else {
					toast.error(
						__( 'Delete failed', 'woo-cart-abandonment-recovery' ),
						{
							description: response.data?.message || '',
						}
					);
				}
				setIsDeleting( false );
				setDeleteModal( { isOpen: false, type: null, id: null } );
			},
			( error ) => {
				toast.error(
					__( 'Delete failed', 'woo-cart-abandonment-recovery' ),
					{
						description: error.data?.message || '',
					}
				);
				setIsDeleting( false );
				setDeleteModal( { isOpen: false, type: null, id: null } );
			},
			true
		);
	};

	const handleConfirmDelete = () => {
		if ( deleteModal.type === 'bulk' ) {
			handleBulkDelete();
		} else {
			handleSingleDelete();
		}
	};

	const handleCancelSelect = () => {
		setSelected( [] );
	};

	const handleViewClick = ( productLink ) => {
		window.open( productLink, '_blank' );
	};

	return (
		<div className="p-4 md:p-8">
			<SectionWrapper className="flex flex-col gap-4">
				<div className="flex flex-col md:flex-row gap-4 md:gap-0 justify-between relative">
					<div className="flex flex-row flex-wrap gap-4 items-center">
						<div className="flex items-center gap-2">
							<Title
								size="sm"
								tag="h1"
								title="Product Reports"
								className="[&_h2]:text-gray-900"
							/>
						</div>
						{ ! isLoading && selected.length > 0 && (
							<div className="flex gap-4 items-center border-0 border-l border-solid border-gray-200">
								<Button
									variant="ghost"
									icon={
										<XMarkIcon className="h-6 w-6 text-gray-500" />
									}
									size="xs"
									className="text-icon-secondary hover:text-icon-primary"
									onClick={ handleCancelSelect }
								/>
								<span className="text-sm font-normal text-gray-500">
									{ selected.length } Selected
								</span>
								<Button
									className="py-2 px-4 bg-red-50 text-red-600 outline-red-600 hover:bg-red-50 hover:outline-red-600"
									size="sm"
									tag="button"
									type="button"
									variant="outline"
									icon={ <TrashIcon className="h-4 w-4" /> }
									iconPosition="left"
									onClick={ handleDeleteTrigger }
								>
									Delete
								</Button>
							</div>
						) }
					</div>
					<div className="flex flex-col lg:flex-row gap-4">
						<Input
							placeholder="Search..."
							prefix={
								<MagnifyingGlassIcon className="h-6 w-6 text-gray-500" />
							}
							size="sm"
							type="text"
							aria-label="Search"
							value={ searchText }
							onChange={ handleSearch }
							className="w-full lg:w-52"
							disabled={ isLoading }
						/>
						<ExportToExcel data={ data } filename="productReport" />
					</div>
				</div>
				{ isLoading ? (
					<div className="flex flex-col gap-4">
						<SkeletonLoader height="40px" />
						{ [ ...Array( 5 ) ].map( ( _, index ) => (
							<SkeletonLoader key={ index } height="50px" />
						) ) }
					</div>
				) : isFeatureBlocked ? (
					// Show dummy data with modal overlay when pro is not active or feature is blocked
					<div className="relative">
						<ProductReportDummyData />
						<ProUpgradeCta
							isVisible={ true }
							highlightText={ __(
								'Unlock Pro Features',
								'woo-cart-abandonment-recovery'
							) }
							mainTitle={ __(
								'Cart Abandonment Recovery Pro is Here 🔥',
								'woo-cart-abandonment-recovery'
							) }
							description={ __(
								"You've seen how emails bring shoppers back. With Pro, you'll unlock advanced tools that recover more carts, boost profits, and grow your store faster.",
								'woo-cart-abandonment-recovery'
							) }
							usps={ [
								__(
									'Product Reports',
									'woo-cart-abandonment-recovery'
								),
								__(
									'SMS + WhatsApp Followups',
									'woo-cart-abandonment-recovery'
								),
								__(
									'Smart Rules',
									'woo-cart-abandonment-recovery'
								),
								__(
									'Advanced Automations',
									'woo-cart-abandonment-recovery'
								),
								__(
									'And More…',
									'woo-cart-abandonment-recovery'
								),
							] }
							actionBtnUrlArgs={
								'utm_source=wcar-dashboard&utm_medium=free-wcar&utm_campaign=go-wcar-pro'
							}
							footerMessage={ '' }
						/>
					</div>
				) : data.length === 0 ? (
					<EmptyBlock
						className="my-2"
						icon={
							<ExclamationTriangleIcon className="h-12 w-12 text-yellow-500" />
						}
						title={ __(
							'No Product Data Yet',
							'woo-cart-abandonment-recovery'
						) }
						description={ __(
							"You'll see product abandonment stats here once customers start adding items to their carts.",
							'woo-cart-abandonment-recovery'
						) }
					/>
				) : (
					<Table checkboxSelection={ true }>
						<Table.Head
							selected={ selected.length > 0 }
							onChangeSelection={ toggleSelectAll }
							indeterminate={
								selected.length > 0 &&
								selected.length < filteredData.length
							}
						>
							<Table.HeadCell>
								{ __(
									'Product Name',
									'woo-cart-abandonment-recovery'
								) }
							</Table.HeadCell>
							<Table.HeadCell>
								{ __(
									'Times Abandoned',
									'woo-cart-abandonment-recovery'
								) }
							</Table.HeadCell>
							<Table.HeadCell>
								{ __(
									'Times Recovered',
									'woo-cart-abandonment-recovery'
								) }
							</Table.HeadCell>
							<Table.HeadCell className="text-right">
								<span className="">
									{ __(
										'Actions',
										'woo-cart-abandonment-recovery'
									) }
								</span>
							</Table.HeadCell>
						</Table.Head>

						{ currentItems.length > 0 ? (
							<Table.Body>
								{ currentItems.map( ( item, index ) => (
									<Table.Row
										key={ index }
										value={ item }
										selected={ selected.includes(
											item.id
										) }
										onChangeSelection={
											handleCheckboxChange
										}
									>
										<Table.Cell>
											<a
												href={ item?.productLink }
												className="w-fit flex gap-2 items-center cursor-pointer no-underline text-inherit hover:text-flamingo-400 focus-visible:text-flamingo-400"
												target="_blank"
												rel="noreferrer"
											>
												<img
													src={ item?.imageUrl }
													alt=""
													className="w-12 h-12 object-cover rounded-md border border-solid border-gray-200"
												/>
												<span>
													{ item.productName } (#
													{ item.id })
												</span>
											</a>
										</Table.Cell>
										<Table.Cell>
											{ item.abandoned }
										</Table.Cell>
										<Table.Cell>
											{ item.recovered }
										</Table.Cell>
										<Table.Cell>
											<div className="flex items-center justify-end gap-2">
												<AppTooltip
													content={ __(
														'View',
														'woo-cart-abandonment-recovery'
													) }
													position="top"
												>
													<Button
														variant="ghost"
														icon={
															<EyeIcon className="h-6 w-6" />
														}
														size="xs"
														className="text-gray-500 hover:text-flamingo-400"
														aria-label={ __(
															'View',
															'woo-cart-abandonment-recovery'
														) }
														onClick={ () =>
															handleViewClick(
																item?.productLink
															)
														}
													/>
												</AppTooltip>
												<AppTooltip
													content={ __(
														'Delete',
														'woo-cart-abandonment-recovery'
													) }
													position="top"
												>
													<Button
														variant="ghost"
														icon={
															<TrashIcon className="h-6 w-6" />
														}
														size="xs"
														className="text-gray-500 hover:text-red-600"
														aria-label={ __(
															'Delete',
															'woo-cart-abandonment-recovery'
														) }
														onClick={ () =>
															setDeleteModal( {
																isOpen: true,
																type: 'single',
																id: item.id,
															} )
														}
													/>
												</AppTooltip>
											</div>
										</Table.Cell>
									</Table.Row>
								) ) }
							</Table.Body>
						) : (
							<tr>
								<td
									colSpan="5"
									className="p-4 w-full text-center"
								>
									{ __(
										'No matching data available',
										'woo-cart-abandonment-recovery'
									) }
								</td>
							</tr>
						) }

						<Table.Footer>
							<div className="flex items-center justify-between w-full">
								<div className="flex items-center gap-2">
									<span className="text-sm font-normal leading-5 text-text-secondary whitespace-nowrap">
										{ __(
											'Items per page:',
											'woo-cart-abandonment-recovery'
										) }
									</span>
									<Select
										onChange={ handleItemsPerPageChange }
										size="md"
										value={ itemsPerPage.toString() }
									>
										<Select.Button />
										<Select.Options>
											<Select.Option value="5">
												5
											</Select.Option>
											<Select.Option value="10">
												10
											</Select.Option>
											<Select.Option value="20">
												20
											</Select.Option>
											<Select.Option value="50">
												50
											</Select.Option>
										</Select.Options>
									</Select>
								</div>
								<Pagination className="w-fit">
									<Pagination.Content>
										<Pagination.Previous
											onClick={ () => {
												if ( currentPage > 1 ) {
													handlePageChange(
														currentPage - 1
													);
												}
											} }
											disabled={ currentPage === 1 }
										/>
										{ Array.from(
											{ length: totalPages },
											( _, i ) => i + 1
										).map( ( page ) => (
											<Pagination.Item
												key={ page }
												isActive={
													currentPage === page
												}
												onClick={ () =>
													handlePageChange( page )
												}
												className={ `${
													currentPage === page
														? 'bg-flamingo-50 text-flamingo-400'
														: ''
												}` }
											>
												{ page }
											</Pagination.Item>
										) ) }
										<Pagination.Next
											onClick={ () => {
												if (
													currentPage < totalPages
												) {
													handlePageChange(
														currentPage + 1
													);
												}
											} }
											disabled={
												currentPage === totalPages
											}
										/>
									</Pagination.Content>
								</Pagination>
							</div>
						</Table.Footer>
					</Table>
				) }
				<ConfirmationModal
					isOpen={ deleteModal.isOpen }
					onClose={ () =>
						setDeleteModal( {
							isOpen: false,
							type: null,
							id: null,
						} )
					}
					onConfirm={ handleConfirmDelete }
					title={
						deleteModal.type === 'bulk'
							? __(
									'Delete Selected Products',
									'woo-cart-abandonment-recovery'
							  )
							: __(
									'Delete Product',
									'woo-cart-abandonment-recovery'
							  )
					}
					message={
						deleteModal.type === 'bulk'
							? sprintf(
									/* translators: %s: number of selected products */
									__(
										'Are you sure you want to delete %s selected product(s)? This action cannot be undone.',
										'woo-cart-abandonment-recovery'
									),
									selected.length
							  )
							: __(
									'Are you sure you want to delete this product? This action cannot be undone.',
									'woo-cart-abandonment-recovery'
							  )
					}
					isLoading={ isDeleting }
				/>
			</SectionWrapper>
		</div>
	);
};

export default Product;
