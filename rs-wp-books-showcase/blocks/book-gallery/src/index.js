import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { escapeHTML } from '@wordpress/escape-html';
import {
    PanelBody,
    SelectControl,
    ToggleControl,
    RangeControl,
    TextControl
} from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

// Register the block
registerBlockType('rswpbs/book-block', {
    title: 'RS WP Book Gallery',
    icon: 'book',
    category: 'widgets',
    attributes: {
        booksPerPage: { type: 'number', default: 8 },
        booksPerRow: { type: 'number', default: 4 },
        categoriesInclude: { type: 'string', default: '' },
        categoriesExclude: { type: 'string', default: '' },
        authorsInclude: { type: 'string', default: '' },
        authorsExclude: { type: 'string', default: '' },
        seriesInclude: { type: 'string', default: '' },
        seriesExclude: { type: 'string', default: '' },
        excludeBooks: { type: 'string', default: '' },
        order: { type: 'string', default: 'DESC' },
        orderby: { type: 'string', default: 'date' },
        showPagination: { type: 'boolean', default: true },
        showAuthor: { type: 'boolean', default: true },
        showTitle: { type: 'boolean', default: true },
        titleType: { type: 'string', default: 'title' },
        showImage: { type: 'boolean', default: true },
        imageType: { type: 'string', default: 'book_cover' },
        imagePosition: { type: 'string', default: 'top' },
        showExcerpt: { type: 'boolean', default: true },
        excerptType: { type: 'string', default: 'excerpt' },
        excerptLimit: { type: 'number', default: 30 },
        showPrice: { type: 'boolean', default: true },
        showBuyButton: { type: 'boolean', default: true },
        showMsl: { type: 'boolean', default: false },
        mslTitleAlign: { type: 'string', default: 'center' },
        contentAlign: { type: 'string', default: 'center' },
        showSearchForm: { type: 'boolean', default: true },
        showSortingForm: { type: 'boolean', default: true },
        showReadMoreButton: { type: 'boolean', default: false },
        showAddToCartButton: { type: 'boolean', default: false },
        showMasonryLayout: { type: 'boolean', default: false },
        heightStretch: { type: 'boolean', default: true }
    },
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        const [shortcodeOutput, setShortcodeOutput] = useState('Loading preview...');
        const [isPremiumUser, setIsPremiumUser] = useState(false);
        // const isPremiumUser = false; // Change this based on real user data
        const premiumLink = 'https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/';

        // Fetch Shortcode Preview
        useEffect(() => {
            const params = Object.fromEntries(
                Object.entries(attributes).map(([key, value]) => [
                    key,
                    typeof value === 'boolean' ? (value ? 'true' : 'false') : value
                ])
            );
            apiFetch({ path: '/rswpbs/v1/plugin-status/' })
                .then((response) => {
                    if (response.isActive) {
                        setIsPremiumUser(true); // Unlock feature if plugin is active
                    }
                })
                .catch(() => {
                    setIsPremiumUser(false); // Keep feature locked if API fails
                });
            apiFetch({ path: `/rswpbs/v1/render-shortcode?${new URLSearchParams(params)}` })
                .then((response) => setShortcodeOutput(response))
                .catch(() => setShortcodeOutput('Error loading preview'));
        }, [attributes]);

        return (
            <div { ...blockProps }>
                <InspectorControls>
                    <PanelBody title="Advanced Query">
                        <RangeControl
                            label="Books Per Page"
                            value={ attributes.booksPerPage }
                            onChange={ (value) => setAttributes({ booksPerPage: value }) }
                            min={ 1 }
                            max={ 50 }
                        />
                        <RangeControl
                            label="Books Per Row"
                            value={ attributes.booksPerRow }
                            onChange={ (value) => setAttributes({ booksPerRow: value }) }
                            min={ 1 }
                            max={ 6 }
                        />
                        <SelectControl
                            label="Order"
                            value={ attributes.order }
                            options={[
                                { label: 'Ascending', value: 'ASC' },
                                { label: 'Descending', value: 'DESC' }
                            ]}
                            onChange={ (value) => setAttributes({ order: value }) }
                        />
                        <SelectControl
                            label={
                                <>
                                    Order By { !isPremiumUser && (
                                        <a href={ escapeHTML(premiumLink) }
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                           PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            value={ attributes.orderby }
                            options={[
                                { label: 'Date', value: 'date' },
                                { label: 'Title', value: 'title' },
                                { label: 'Random', value: 'rand' }
                            ]}
                            onChange={ (value) => setAttributes({ orderby: value }) }
                            disabled={!isPremiumUser}
                        />
                        <TextControl
                            label="Include Categories (IDs)"
                            value={ attributes.categoriesInclude }
                            onChange={ (value) => setAttributes({ categoriesInclude: value }) }
                            placeholder="Example: 665, 558"
                        />
                        <TextControl
                            label="Exclude Categories (IDs)"
                            value={ attributes.categoriesExclude }
                            onChange={ (value) => setAttributes({ categoriesExclude: value }) }
                            placeholder="Example: 778, 225"
                        />
                        <TextControl
                            label="Include Authors (IDs)"
                            value={ attributes.authorsInclude }
                            onChange={ (value) => setAttributes({ authorsInclude: value }) }
                            placeholder="Example: 671, 497"
                        />
                        <TextControl
                            label="Exclude Authors (IDs)"
                            value={ attributes.authorsExclude }
                            onChange={ (value) => setAttributes({ authorsExclude: value }) }
                            placeholder="Example: 184, 758"
                        />
                        <TextControl
                            label={
                                <>
                                    Include Series (IDs) { !isPremiumUser && (
                                        <a href={ escapeHTML(premiumLink) }
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                           PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            value={ attributes.seriesInclude }
                            onChange={ (value) => setAttributes({ seriesInclude: value }) }
                            disabled={!isPremiumUser}
                            readOnly={!isPremiumUser}
                            placeholder="Example: 987, 575"
                            style={ !isPremiumUser ? { backgroundColor: '#f5f5f5', color: '#888', cursor: 'not-allowed' } : {} }
                        />
                        <TextControl
                            label={
                                <>
                                    Exclude Series (IDs) { !isPremiumUser && (
                                        <a href={ escapeHTML(premiumLink) }
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                           PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            value={ attributes.seriesExclude }
                            onChange={ (value) => setAttributes({ seriesExclude: value }) }
                            disabled={!isPremiumUser}
                            readOnly={!isPremiumUser}
                            placeholder="Example: 481, 578"
                            style={ !isPremiumUser ? { backgroundColor: '#f5f5f5', color: '#888', cursor: 'not-allowed' } : {} }
                        />
                        <TextControl
                            label="Exclude Books (IDs)"
                            value={ attributes.excludeBooks }
                            onChange={ (value) => setAttributes({ excludeBooks: value }) }
                            placeholder="Example: 788, 255"
                        />
                        <ToggleControl
                            label="Show Pagination"
                            checked={ attributes.showPagination }
                            onChange={ (value) => setAttributes({ showPagination: value }) }
                        />
                    </PanelBody>

                    <PanelBody title="Display Settings">
                        <ToggleControl
                            label={
                                <>
                                    Show Masonry Layout { !isPremiumUser && (
                                        <a
                                            href={ escapeHTML(premiumLink) }
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}
                                        >
                                            PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            checked={ attributes.showMasonryLayout }
                            onChange={ (value) => setAttributes({ showMasonryLayout: value }) }
                            disabled={!isPremiumUser}
                        />
                        <ToggleControl
                            label={
                                <>
                                    Height Stretch { !isPremiumUser && (
                                        <a
                                            href={ escapeHTML(premiumLink) }
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}
                                        >
                                            PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            checked={ attributes.heightStretch }
                            onChange={ (value) => setAttributes({ heightStretch: value }) }
                            disabled={!isPremiumUser}
                        />
                        <ToggleControl
                            label="Show Search Form"
                            checked={ attributes.showSearchForm }
                            onChange={ (value) => setAttributes({ showSearchForm: value }) }
                        />
                        <ToggleControl
                            label="Show Sorting Form"
                            checked={ attributes.showSortingForm }
                            onChange={ (value) => setAttributes({ showSortingForm: value }) }
                        />
                        <ToggleControl
                            label="Show Book Title"
                            checked={ attributes.showTitle }
                            onChange={ (value) => setAttributes({ showTitle: value }) }
                        />
                        <ToggleControl
                            label="Show Book Image"
                            checked={ attributes.showImage }
                            onChange={ (value) => setAttributes({ showImage: value }) }
                        />
                        { attributes.showImage && (
                            <>
                                <SelectControl
                                    label="Book Image Type"
                                    value={ attributes.imageType }
                                    options={[
                                        { label: 'Book Cover', value: 'book_cover' },
                                        { label: 'Book Mockup', value: 'book_mockup' },
                                    ]}
                                    onChange={(value) => setAttributes({ imageType: value })}
                                />
                                <SelectControl
                                    label="Image Position"
                                    value={ attributes.imagePosition }
                                    options={[
                                        { label: 'Top', value: 'top' },
                                        { label: 'Left', value: 'left' },
                                        { label: 'Right', value: 'right' }
                                    ]}
                                    onChange={(value) => setAttributes({ imagePosition: value })}
                                />
                            </>
                        )}
                        <ToggleControl
                            label="Show Book Author"
                            checked={ attributes.showAuthor }
                            onChange={ (value) => setAttributes({ showAuthor: value }) }
                        />
                        <ToggleControl
                            label="Show Book Excerpt"
                            checked={ attributes.showExcerpt }
                            onChange={ (value) => setAttributes({ showExcerpt: value }) }
                        />
                        { attributes.showExcerpt && (
                            <RangeControl
                                label="Excerpt Limit"
                                value={ attributes.excerptLimit }
                                onChange={ (value) => setAttributes({ excerptLimit: value }) }
                                min={ 10 }
                                max={ 100 }
                            />
                        ) }
                        <ToggleControl
                            label="Show Book Price"
                            checked={ attributes.showPrice }
                            onChange={ (value) => setAttributes({ showPrice: value }) }
                        />
                        <ToggleControl
                            label="Show Book Buy Button"
                            checked={ attributes.showBuyButton }
                            onChange={ (value) => setAttributes({ showBuyButton: value }) }
                        />
                        <ToggleControl
                            label={
                                <>
                                    Show Add To Cart { !isPremiumUser && (
                                        <a
                                            href={ escapeHTML(premiumLink) }
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}
                                        >
                                            PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            checked={ attributes.showAddToCartButton }
                            onChange={ (value) => setAttributes({ showAddToCartButton: value }) }
                            disabled={!isPremiumUser}
                        />
                        <ToggleControl
                            label={
                                <>
                                    Show Read More { !isPremiumUser && (
                                        <a
                                            href={ escapeHTML(premiumLink) }
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}
                                        >
                                            PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            checked={ attributes.showReadMoreButton }
                            onChange={ (value) => setAttributes({ showReadMoreButton: value }) }
                            disabled={!isPremiumUser}
                        />
                    </PanelBody>
                </InspectorControls>
                <div dangerouslySetInnerHTML={{ __html: shortcodeOutput }} />
            </div>
        );
    },
    save: () => {
        return null; // Server-side rendering
    }
});
