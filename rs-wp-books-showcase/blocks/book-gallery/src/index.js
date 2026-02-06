import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { escapeHTML } from '@wordpress/escape-html';
import {
    PanelBody,
    SelectControl,
    ToggleControl,
    RangeControl,
    TextControl,
    ColorPicker,
    TabPanel,
    RadioControl
} from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

registerBlockType('rswpbs/book-block', {
    title: 'RS WP Book Gallery',
    icon: 'book',
    category: 'widgets',
    attributes: {
        // ... (all your attributes remain the same)
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
        heightStretch: { type: 'boolean', default: true },
        align: { type: 'string', default: 'center' },
        buttonBackgroundColorNormal: { type: 'string', default: '#0073aa' },
        buttonTextColorNormal: { type: 'string', default: '#ffffff' },
        buttonBorderRadiusNormal: { type: 'number', default: 4 },
        buttonPaddingNormal: { type: 'string', default: '10px 20px' },
        buttonBackgroundColorHover: { type: 'string', default: '#005d87' },
        buttonTextColorHover: { type: 'string', default: '#ffffff' },
        buttonBorderRadiusHover: { type: 'number', default: 4 },
        buttonPaddingHover: { type: 'string', default: '10px 20px' }
    },
edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps({
            className: `align${attributes.align || 'center'}`
        });
        const [shortcodeOutput, setShortcodeOutput] = useState('Loading preview...');
        const [isPremiumUser, setIsPremiumUser] = useState(false);
        const premiumLink = 'https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/';

        // 1. Initial state is now null to hide picker
        const [colorType, setColorType] = useState(null); // For Button Styling

        // 2. New function to toggle the color picker's visibility
        const toggleColorPicker = ( newType ) => {
            if ( colorType === newType ) {
                // User clicked the same button again, so close the picker
                setColorType(null);
            } else {
                // User clicked a new button, so open/switch the picker
                setColorType(newType);
            }
        };

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
                        setIsPremiumUser(true);
                    }
                })
                .catch(() => {
                    setIsPremiumUser(false);
                });
            apiFetch({ path: `/rswpbs/v1/render-shortcode?${new URLSearchParams(params)}` })
                .then((response) => setShortcodeOutput(response))
                .catch(() => setShortcodeOutput('Error loading preview'));
        }, [attributes]);


        return (
            <div {...blockProps}>
                <InspectorControls>

                    {/* === PANEL 1: QUERY & FILTERING === */}
                    <PanelBody title="Query & Filtering" initialOpen={true}>
                        {/* ... All controls from this panel ... */}
                        <RangeControl
                            label="Books Per Page"
                            value={attributes.booksPerPage}
                            onChange={(value) => setAttributes({ booksPerPage: value })}
                            min={1}
                            max={50}
                        />
                        <RangeControl
                            label="Books Per Row"
                            value={attributes.booksPerRow}
                            onChange={(value) => setAttributes({ booksPerRow: value })}
                            min={1}
                            max={6}
                        />
                        <SelectControl
                            label="Order"
                            value={attributes.order}
                            options={[
                                { label: 'Ascending', value: 'ASC' },
                                { label: 'Descending', value: 'DESC' }
                            ]}
                            onChange={(value) => setAttributes({ order: value })}
                        />
                        <SelectControl
                            label={
                                <>
                                    Order By { !isPremiumUser && (
                                        <a href={escapeHTML(premiumLink)} target="_blank" rel="noopener noreferrer" style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                             PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            value={attributes.orderby}
                            options={[
                                { label: 'Date', value: 'date' },
                                { label: 'Title', value: 'title' },
                                { label: 'Random', value: 'rand' }
                            ]}
                            onChange={(value) => setAttributes({ orderby: value })}
                            disabled={!isPremiumUser}
                        />
                        <TextControl
                            label="Include Categories (IDs)"
                            value={attributes.categoriesInclude}
                            onChange={(value) => setAttributes({ categoriesInclude: value })}
                            placeholder="Example: 665, 558"
                        />
                        <TextControl
                            label="Exclude Categories (IDs)"
                            value={attributes.categoriesExclude}
                            onChange={(value) => setAttributes({ categoriesExclude: value })}
                            placeholder="Example: 778, 225"
                        />
                        <TextControl
                            label="Include Authors (IDs)"
                            value={attributes.authorsInclude}
                            onChange={(value) => setAttributes({ authorsInclude: value })}
                            placeholder="Example: 671, 497"
                        />
                        <TextControl
                            label="Exclude Authors (IDs)"
                            value={attributes.authorsExclude}
                            onChange={(value) => setAttributes({ authorsExclude: value })}
                            placeholder="Example: 184, 758"
                        />
                        <TextControl
                            label={
                                <>
                                    Include Series (IDs) { !isPremiumUser && (
                                        <a href={escapeHTML(premiumLink)} target="_blank" rel="noopener noreferrer" style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                             PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            value={attributes.seriesInclude}
                            onChange={(value) => setAttributes({ seriesInclude: value })}
                            disabled={!isPremiumUser}
                            readOnly={!isPremiumUser}
                            placeholder="Example: 987, 575"
                            style={!isPremiumUser ? { backgroundColor: '#f5f5f5', color: '#888', cursor: 'not-allowed' } : {}}
                        />
                        <TextControl
                            label={
                                <>
                                    Exclude Series (IDs) { !isPremiumUser && (
                                        <a href={escapeHTML(premiumLink)} target="_blank" rel="noopener noreferrer" style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                             PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            value={attributes.seriesExclude}
                            onChange={(value) => setAttributes({ seriesExclude: value })}
                            disabled={!isPremiumUser}
                            readOnly={!isPremiumUser}
                            placeholder="Example: 481, 578"
                            style={!isPremiumUser ? { backgroundColor: '#f5f5f5', color: '#888', cursor: 'not-allowed' } : {}}
                        />
                        <TextControl
                            label="Exclude Books (IDs)"
                            value={attributes.excludeBooks}
                            onChange={(value) => setAttributes({ excludeBooks: value })}
                            placeholder="Example: 788, 255"
                        />
                    </PanelBody>

                    {/* === PANEL 2: LAYOUT & FORMS === */}
                    <PanelBody title="Layout & Forms" initialOpen={false}>
                        {/* ... All controls from this panel ... */}
                        <ToggleControl
                            label="Show Pagination"
                            checked={attributes.showPagination}
                            onChange={(value) => setAttributes({ showPagination: value })}
                        />
                        <ToggleControl
                            label="Show Search Form"
                            checked={attributes.showSearchForm}
                            onChange={(value) => setAttributes({ showSearchForm: value })}
                        />
                        <ToggleControl
                            label="Show Sorting Form"
                            checked={attributes.showSortingForm}
                            onChange={(value) => setAttributes({ showSortingForm: value })}
                        />
                        <ToggleControl
                            label={
                                <>
                                    Show Masonry Layout { !isPremiumUser && (
                                        <a href={escapeHTML(premiumLink)} target="_blank" rel="noopener noreferrer" style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                             PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            checked={attributes.showMasonryLayout}
                            onChange={(value) => setAttributes({ showMasonryLayout: value })}
                            disabled={!isPremiumUser}
                        />
                        <ToggleControl
                            label={
                                <>
                                    Height Stretch { !isPremiumUser && (
                                        <a href={escapeHTML(premiumLink)} target="_blank" rel="noopener noreferrer" style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                             PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            checked={attributes.heightStretch}
                            onChange={(value) => setAttributes({ heightStretch: value })}
                            disabled={!isPremiumUser}
                        />
                        <SelectControl
                            label="Content Alignment"
                            value={attributes.contentAlign}
                            options={[
                                { label: 'Left', value: 'left' },
                                { label: 'Center', value: 'center' },
                                { label: 'Right', value: 'right' }
                            ]}
                            onChange={(value) => setAttributes({ contentAlign: value })}
                        />
                    </PanelBody>

                    {/* === PANEL 3: CONTENT DISPLAY === */}
                    <PanelBody title="Content Display" initialOpen={false}>
                        {/* ... All controls from this panel ... */}
                        <ToggleControl
                            label="Show Book Title"
                            checked={attributes.showTitle}
                            onChange={(value) => setAttributes({ showTitle: value })}
                        />
                        {attributes.showTitle && (
                            <SelectControl
                                label="Title Type"
                                value={attributes.titleType}
                                options={[
                                    { label: 'Title', value: 'title' },
                                ]}
                                onChange={(value) => setAttributes({ titleType: value })}
                                help="Select the source for the title."
                            />
                        )}
                        <hr/>
                        <ToggleControl
                            label="Show Book Image"
                            checked={attributes.showImage}
                            onChange={(value) => setAttributes({ showImage: value })}
                        />
                        {attributes.showImage && (
                            <>
                                <SelectControl
                                    label="Book Image Type"
                                    value={attributes.imageType}
                                    options={[
                                        { label: 'Book Cover', value: 'book_cover' },
                                        { label: 'Book Mockup', value: 'book_mockup' },
                                    ]}
                                    onChange={(value) => setAttributes({ imageType: value })}
                                />
                                <SelectControl
                                    label="Image Position"
                                    value={attributes.imagePosition}
                                    options={[
                                        { label: 'Top', value: 'top' },
                                        { label: 'Left', value: 'left' },
                                        { label: 'Right', value: 'right' }
                                    ]}
                                    onChange={(value) => setAttributes({ imagePosition: value })}
                                />
                            </>
                        )}
                        <hr/>
                        <ToggleControl
                            label="Show Book Author"
                            checked={attributes.showAuthor}
                            onChange={(value) => setAttributes({ showAuthor: value })}
                        />
                        <hr/>
                        <ToggleControl
                            label="Show Book Excerpt"
                            checked={attributes.showExcerpt}
                            onChange={(value) => setAttributes({ showExcerpt: value })}
                        />
                        {attributes.showExcerpt && (
                            <>
                                <RangeControl
                                    label="Excerpt Limit (words)"
                                    value={attributes.excerptLimit}
                                    onChange={(value) => setAttributes({ excerptLimit: value })}
                                    min={10}
                                    max={100}
                                />
                                <SelectControl
                                    label="Excerpt Type"
                                    value={attributes.excerptType}
                                    options={[
                                        { label: 'Excerpt', value: 'excerpt' },
                                        { label: 'Content', value: 'content' },
                                    ]}
                                    onChange={(value) => setAttributes({ excerptType: value })}
                                    help="Source for the excerpt."
                                />
                            </>
                        )}
                        <hr/>
                        <ToggleControl
                            label="Show Book Price"
                            checked={attributes.showPrice}
                            onChange={(value) => setAttributes({ showPrice: value })}
                        />
                        <ToggleControl
                            label="Show Book Buy Button"
                            checked={attributes.showBuyButton}
                            onChange={(value) => setAttributes({ showBuyButton: value })}
                        />
                        <ToggleControl
                            label={
                                <>
                                    Show Add To Cart { !isPremiumUser && (
                                        <a href={escapeHTML(premiumLink)} target="_blank" rel="noopener noreferrer" style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                             PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            checked={attributes.showAddToCartButton}
                            onChange={(value) => setAttributes({ showAddToCartButton: value })}
                            disabled={!isPremiumUser}
                        />
                        <ToggleControl
                            label={
                                <>
                                    Show Read More { !isPremiumUser && (
                                        <a href={escapeHTML(premiumLink)} target="_blank" rel="noopener noreferrer" style={{ color: 'red', fontWeight: 'bold', marginLeft: '8px', textDecoration: 'none' }}>
                                             PRO 🔒
                                        </a>
                                    )}
                                </>
                            }
                            checked={attributes.showReadMoreButton}
                            onChange={(value) => setAttributes({ showReadMoreButton: value })}
                            disabled={!isPremiumUser}
                        />
                        <hr/>
                        <ToggleControl
                            label="Show MSL (Meta/Share/Like)"
                            checked={attributes.showMsl}
                            onChange={(value) => setAttributes({ showMsl: value })}
                        />
                        {attributes.showMsl && (
                            <SelectControl
                                label="MSL Title Alignment"
                                value={attributes.mslTitleAlign}
                                options={[
                                    { label: 'Left', value: 'left' },
                                    { label: 'Center', value: 'center' },
                                    { label: 'Right', value: 'right' }
                                ]}
                                onChange={(value) => setAttributes({ mslTitleAlign: value })}
                            />
                        )}
                    </PanelBody>

                    {/* === PANEL 4: BUTTON STYLING (MODIFIED) === */}
                    <PanelBody title="Button Styling" initialOpen={false}>
                        <TabPanel
                            tabs={[
                                { name: 'normal', title: 'Normal', className: 'button-normal-tab' },
                                { name: 'hover', title: 'Hover', className: 'button-hover-tab' }
                            ]}
                            initialTabName="normal"
                            onSelect={(tabName) => {/* Handle tab switch if needed */}}
                        >
                            {(tab) => {
                                const isNormal = tab.name === 'normal';
                                return (
                                    <>
                                        <RadioControl
                                            label="Color"
                                            selected={colorType}
                                            options={[
                                                { label: 'Text', value: 'text' },
                                                { label: 'Background', value: 'background' }
                                            ]}
                                            // Use the new toggle function here
                                            onChange={toggleColorPicker}
                                        />

                                        {/* 3. Conditionally render the ColorPicker */}
                                        {colorType && (
                                            <ColorPicker
                                                label={`Button ${colorType === 'text' ? 'Text' : 'Background'} Color`}
                                                color={colorType === 'text' ? (isNormal ? attributes.buttonTextColorNormal : attributes.buttonTextColorHover) : (isNormal ? attributes.buttonBackgroundColorNormal : attributes.buttonBackgroundColorHover)}
                                                onChangeComplete={(value) => {
                                                    setAttributes({
                                                        [isNormal ? (colorType === 'text' ? 'buttonTextColorNormal' : 'buttonBackgroundColorNormal') : (colorType === 'text' ? 'buttonTextColorHover' : 'buttonBackgroundColorHover')]: value.hex
                                                    });
                                                }}
                                                defaultValue={colorType === 'text' ? '#ffffff' : (isNormal ? '#0073aa' : '#005d87')}
                                            />
                                        )}

                                        <RangeControl
                                            label="Button Border Radius"
                                            value={isNormal ? attributes.buttonBorderRadiusNormal : attributes.buttonBorderRadiusHover}
                                            onChange={(value) => setAttributes({
                                                [isNormal ? 'buttonBorderRadiusNormal' : 'buttonBorderRadiusHover']: value
                                            })}
                                            min={0}
                                            max={50}
                                        />
                                        <TextControl
                                            label="Button Padding"
                                            value={isNormal ? attributes.buttonPaddingNormal : attributes.buttonPaddingHover}
                                            onChange={(value) => setAttributes({
                                                [isNormal ? 'buttonPaddingNormal' : 'buttonPaddingHover']: value
                                            })}
                                            placeholder="e.g., 10px 20px"
                                        />
                                    </>
                                );
                            }}
                        </TabPanel>
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