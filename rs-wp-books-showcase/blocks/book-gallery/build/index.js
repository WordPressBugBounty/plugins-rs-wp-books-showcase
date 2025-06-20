/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/escape-html":
/*!************************************!*\
  !*** external ["wp","escapeHtml"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["escapeHtml"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/escape-html */ "@wordpress/escape-html");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");







(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('rswpbs/book-block', {
  title: 'RS WP Book Gallery',
  icon: 'book',
  category: 'widgets',
  attributes: {
    booksPerPage: {
      type: 'number',
      default: 8
    },
    booksPerRow: {
      type: 'number',
      default: 4
    },
    categoriesInclude: {
      type: 'string',
      default: ''
    },
    categoriesExclude: {
      type: 'string',
      default: ''
    },
    authorsInclude: {
      type: 'string',
      default: ''
    },
    authorsExclude: {
      type: 'string',
      default: ''
    },
    seriesInclude: {
      type: 'string',
      default: ''
    },
    seriesExclude: {
      type: 'string',
      default: ''
    },
    excludeBooks: {
      type: 'string',
      default: ''
    },
    order: {
      type: 'string',
      default: 'DESC'
    },
    orderby: {
      type: 'string',
      default: 'date'
    },
    showPagination: {
      type: 'boolean',
      default: true
    },
    showAuthor: {
      type: 'boolean',
      default: true
    },
    showTitle: {
      type: 'boolean',
      default: true
    },
    titleType: {
      type: 'string',
      default: 'title'
    },
    showImage: {
      type: 'boolean',
      default: true
    },
    imageType: {
      type: 'string',
      default: 'book_cover'
    },
    imagePosition: {
      type: 'string',
      default: 'top'
    },
    showExcerpt: {
      type: 'boolean',
      default: true
    },
    excerptType: {
      type: 'string',
      default: 'excerpt'
    },
    excerptLimit: {
      type: 'number',
      default: 30
    },
    showPrice: {
      type: 'boolean',
      default: true
    },
    showBuyButton: {
      type: 'boolean',
      default: true
    },
    showMsl: {
      type: 'boolean',
      default: false
    },
    mslTitleAlign: {
      type: 'string',
      default: 'center'
    },
    contentAlign: {
      type: 'string',
      default: 'center'
    },
    showSearchForm: {
      type: 'boolean',
      default: true
    },
    showSortingForm: {
      type: 'boolean',
      default: true
    },
    showReadMoreButton: {
      type: 'boolean',
      default: false
    },
    showAddToCartButton: {
      type: 'boolean',
      default: false
    },
    showMasonryLayout: {
      type: 'boolean',
      default: false
    },
    heightStretch: {
      type: 'boolean',
      default: true
    },
    align: {
      type: 'string',
      default: 'center'
    },
    buttonBackgroundColorNormal: {
      type: 'string',
      default: '#0073aa'
    },
    buttonTextColorNormal: {
      type: 'string',
      default: '#ffffff'
    },
    buttonBorderRadiusNormal: {
      type: 'number',
      default: 4
    },
    buttonPaddingNormal: {
      type: 'string',
      default: '10px 20px'
    },
    buttonBackgroundColorHover: {
      type: 'string',
      default: '#005d87'
    },
    buttonTextColorHover: {
      type: 'string',
      default: '#ffffff'
    },
    buttonBorderRadiusHover: {
      type: 'number',
      default: 4
    },
    buttonPaddingHover: {
      type: 'string',
      default: '10px 20px'
    }
  },
  edit: ({
    attributes,
    setAttributes
  }) => {
    const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
      className: `align${attributes.align || 'center'}`
    });
    const [shortcodeOutput, setShortcodeOutput] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)('Loading preview...');
    const [isPremiumUser, setIsPremiumUser] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)(false);
    const premiumLink = 'https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/';
    const [colorType, setColorType] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)('background'); // Default to background color

    (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useEffect)(() => {
      const params = Object.fromEntries(Object.entries(attributes).map(([key, value]) => [key, typeof value === 'boolean' ? value ? 'true' : 'false' : value]));
      _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__({
        path: '/rswpbs/v1/plugin-status/'
      }).then(response => {
        if (response.isActive) {
          setIsPremiumUser(true);
        }
      }).catch(() => {
        setIsPremiumUser(false);
      });
      _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__({
        path: `/rswpbs/v1/render-shortcode?${new URLSearchParams(params)}`
      }).then(response => setShortcodeOutput(response)).catch(() => setShortcodeOutput('Error loading preview'));
    }, [attributes]);
    const buttonTabs = [{
      name: 'normal',
      title: 'Normal',
      className: 'button-normal-tab'
    }, {
      name: 'hover',
      title: 'Hover',
      className: 'button-hover-tab'
    }];
    const renderTabContent = tabName => {
      const isNormal = tabName === 'normal';
      const currentBackgroundColor = isNormal ? attributes.buttonBackgroundColorNormal : attributes.buttonBackgroundColorHover;
      const currentTextColor = isNormal ? attributes.buttonTextColorNormal : attributes.buttonTextColorHover;
      const defaultBackgroundColor = isNormal ? '#0073aa' : '#005d87';
      const defaultTextColor = '#ffffff';
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.RadioControl, {
          label: "Color",
          selected: colorType,
          options: [{
            label: 'Text',
            value: 'text'
          }, {
            label: 'Background',
            value: 'background'
          }],
          onChange: setColorType
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ColorPicker, {
          label: `Button ${colorType === 'text' ? 'Text' : 'Background'} Color`,
          color: colorType === 'text' ? currentTextColor : currentBackgroundColor,
          onChangeComplete: value => {
            setAttributes({
              [isNormal ? colorType === 'text' ? 'buttonTextColorNormal' : 'buttonBackgroundColorNormal' : colorType === 'text' ? 'buttonTextColorHover' : 'buttonBackgroundColorHover']: value.hex
            });
          },
          defaultValue: colorType === 'text' ? defaultTextColor : defaultBackgroundColor
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.RangeControl, {
          label: "Button Border Radius",
          value: isNormal ? attributes.buttonBorderRadiusNormal : attributes.buttonBorderRadiusHover,
          onChange: value => setAttributes({
            [isNormal ? 'buttonBorderRadiusNormal' : 'buttonBorderRadiusHover']: value
          }),
          min: 0,
          max: 50
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
          label: "Button Padding",
          value: isNormal ? attributes.buttonPaddingNormal : attributes.buttonPaddingHover,
          onChange: value => setAttributes({
            [isNormal ? 'buttonPaddingNormal' : 'buttonPaddingHover']: value
          }),
          placeholder: "e.g., 10px 20px"
        })]
      });
    };
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
      ...blockProps,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
          title: "Advanced Query",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.RangeControl, {
            label: "Books Per Page",
            value: attributes.booksPerPage,
            onChange: value => setAttributes({
              booksPerPage: value
            }),
            min: 1,
            max: 50
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.RangeControl, {
            label: "Books Per Row",
            value: attributes.booksPerRow,
            onChange: value => setAttributes({
              booksPerRow: value
            }),
            min: 1,
            max: 6
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
            label: "Order",
            value: attributes.order,
            options: [{
              label: 'Ascending',
              value: 'ASC'
            }, {
              label: 'Descending',
              value: 'DESC'
            }],
            onChange: value => setAttributes({
              order: value
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
            label: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
              children: ["Order By ", !isPremiumUser && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("a", {
                href: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)(premiumLink),
                target: "_blank",
                rel: "noopener noreferrer",
                style: {
                  color: 'red',
                  fontWeight: 'bold',
                  marginLeft: '8px',
                  textDecoration: 'none'
                },
                children: "PRO \uD83D\uDD12"
              })]
            }),
            value: attributes.orderby,
            options: [{
              label: 'Date',
              value: 'date'
            }, {
              label: 'Title',
              value: 'title'
            }, {
              label: 'Random',
              value: 'rand'
            }],
            onChange: value => setAttributes({
              orderby: value
            }),
            disabled: !isPremiumUser
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
            label: "Include Categories (IDs)",
            value: attributes.categoriesInclude,
            onChange: value => setAttributes({
              categoriesInclude: value
            }),
            placeholder: "Example: 665, 558"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
            label: "Exclude Categories (IDs)",
            value: attributes.categoriesExclude,
            onChange: value => setAttributes({
              categoriesExclude: value
            }),
            placeholder: "Example: 778, 225"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
            label: "Include Authors (IDs)",
            value: attributes.authorsInclude,
            onChange: value => setAttributes({
              authorsInclude: value
            }),
            placeholder: "Example: 671, 497"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
            label: "Exclude Authors (IDs)",
            value: attributes.authorsExclude,
            onChange: value => setAttributes({
              authorsExclude: value
            }),
            placeholder: "Example: 184, 758"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
            label: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
              children: ["Include Series (IDs) ", !isPremiumUser && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("a", {
                href: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)(premiumLink),
                target: "_blank",
                rel: "noopener noreferrer",
                style: {
                  color: 'red',
                  fontWeight: 'bold',
                  marginLeft: '8px',
                  textDecoration: 'none'
                },
                children: "PRO \uD83D\uDD12"
              })]
            }),
            value: attributes.seriesInclude,
            onChange: value => setAttributes({
              seriesInclude: value
            }),
            disabled: !isPremiumUser,
            readOnly: !isPremiumUser,
            placeholder: "Example: 987, 575",
            style: !isPremiumUser ? {
              backgroundColor: '#f5f5f5',
              color: '#888',
              cursor: 'not-allowed'
            } : {}
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
            label: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
              children: ["Exclude Series (IDs) ", !isPremiumUser && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("a", {
                href: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)(premiumLink),
                target: "_blank",
                rel: "noopener noreferrer",
                style: {
                  color: 'red',
                  fontWeight: 'bold',
                  marginLeft: '8px',
                  textDecoration: 'none'
                },
                children: "PRO \uD83D\uDD12"
              })]
            }),
            value: attributes.seriesExclude,
            onChange: value => setAttributes({
              seriesExclude: value
            }),
            disabled: !isPremiumUser,
            readOnly: !isPremiumUser,
            placeholder: "Example: 481, 578",
            style: !isPremiumUser ? {
              backgroundColor: '#f5f5f5',
              color: '#888',
              cursor: 'not-allowed'
            } : {}
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
            label: "Exclude Books (IDs)",
            value: attributes.excludeBooks,
            onChange: value => setAttributes({
              excludeBooks: value
            }),
            placeholder: "Example: 788, 255"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: "Show Pagination",
            checked: attributes.showPagination,
            onChange: value => setAttributes({
              showPagination: value
            })
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
          title: "Display Settings",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
              children: ["Show Masonry Layout ", !isPremiumUser && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("a", {
                href: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)(premiumLink),
                target: "_blank",
                rel: "noopener noreferrer",
                style: {
                  color: 'red',
                  fontWeight: 'bold',
                  marginLeft: '8px',
                  textDecoration: 'none'
                },
                children: "PRO \uD83D\uDD12"
              })]
            }),
            checked: attributes.showMasonryLayout,
            onChange: value => setAttributes({
              showMasonryLayout: value
            }),
            disabled: !isPremiumUser
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
              children: ["Height Stretch ", !isPremiumUser && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("a", {
                href: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)(premiumLink),
                target: "_blank",
                rel: "noopener noreferrer",
                style: {
                  color: 'red',
                  fontWeight: 'bold',
                  marginLeft: '8px',
                  textDecoration: 'none'
                },
                children: "PRO \uD83D\uDD12"
              })]
            }),
            checked: attributes.heightStretch,
            onChange: value => setAttributes({
              heightStretch: value
            }),
            disabled: !isPremiumUser
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: "Show Search Form",
            checked: attributes.showSearchForm,
            onChange: value => setAttributes({
              showSearchForm: value
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: "Show Sorting Form",
            checked: attributes.showSortingForm,
            onChange: value => setAttributes({
              showSortingForm: value
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: "Show Book Title",
            checked: attributes.showTitle,
            onChange: value => setAttributes({
              showTitle: value
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: "Show Book Image",
            checked: attributes.showImage,
            onChange: value => setAttributes({
              showImage: value
            })
          }), attributes.showImage && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
              label: "Book Image Type",
              value: attributes.imageType,
              options: [{
                label: 'Book Cover',
                value: 'book_cover'
              }, {
                label: 'Book Mockup',
                value: 'book_mockup'
              }],
              onChange: value => setAttributes({
                imageType: value
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
              label: "Image Position",
              value: attributes.imagePosition,
              options: [{
                label: 'Top',
                value: 'top'
              }, {
                label: 'Left',
                value: 'left'
              }, {
                label: 'Right',
                value: 'right'
              }],
              onChange: value => setAttributes({
                imagePosition: value
              })
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: "Show Book Author",
            checked: attributes.showAuthor,
            onChange: value => setAttributes({
              showAuthor: value
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: "Show Book Excerpt",
            checked: attributes.showExcerpt,
            onChange: value => setAttributes({
              showExcerpt: value
            })
          }), attributes.showExcerpt && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.RangeControl, {
            label: "Excerpt Limit",
            value: attributes.excerptLimit,
            onChange: value => setAttributes({
              excerptLimit: value
            }),
            min: 10,
            max: 100
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: "Show Book Price",
            checked: attributes.showPrice,
            onChange: value => setAttributes({
              showPrice: value
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: "Show Book Buy Button",
            checked: attributes.showBuyButton,
            onChange: value => setAttributes({
              showBuyButton: value
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
              children: ["Show Add To Cart ", !isPremiumUser && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("a", {
                href: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)(premiumLink),
                target: "_blank",
                rel: "noopener noreferrer",
                style: {
                  color: 'red',
                  fontWeight: 'bold',
                  marginLeft: '8px',
                  textDecoration: 'none'
                },
                children: "PRO \uD83D\uDD12"
              })]
            }),
            checked: attributes.showAddToCartButton,
            onChange: value => setAttributes({
              showAddToCartButton: value
            }),
            disabled: !isPremiumUser
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
            label: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
              children: ["Show Read More ", !isPremiumUser && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("a", {
                href: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)(premiumLink),
                target: "_blank",
                rel: "noopener noreferrer",
                style: {
                  color: 'red',
                  fontWeight: 'bold',
                  marginLeft: '8px',
                  textDecoration: 'none'
                },
                children: "PRO \uD83D\uDD12"
              })]
            }),
            checked: attributes.showReadMoreButton,
            onChange: value => setAttributes({
              showReadMoreButton: value
            }),
            disabled: !isPremiumUser
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
          title: "Button Styling",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TabPanel, {
            tabs: [{
              name: 'normal',
              title: 'Normal',
              className: 'button-normal-tab'
            }, {
              name: 'hover',
              title: 'Hover',
              className: 'button-hover-tab'
            }],
            initialTabName: "normal",
            onSelect: tabName => {/* Handle tab switch if needed */},
            children: tab => {
              const isNormal = tab.name === 'normal';
              return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
                children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.RadioControl, {
                  label: "Color",
                  selected: colorType,
                  options: [{
                    label: 'Text',
                    value: 'text'
                  }, {
                    label: 'Background',
                    value: 'background'
                  }],
                  onChange: setColorType
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ColorPicker, {
                  label: `Button ${colorType === 'text' ? 'Text' : 'Background'} Color`,
                  color: colorType === 'text' ? isNormal ? attributes.buttonTextColorNormal : attributes.buttonTextColorHover : isNormal ? attributes.buttonBackgroundColorNormal : attributes.buttonBackgroundColorHover,
                  onChangeComplete: value => {
                    setAttributes({
                      [isNormal ? colorType === 'text' ? 'buttonTextColorNormal' : 'buttonBackgroundColorNormal' : colorType === 'text' ? 'buttonTextColorHover' : 'buttonBackgroundColorHover']: value.hex
                    });
                  },
                  defaultValue: colorType === 'text' ? '#ffffff' : isNormal ? '#0073aa' : '#005d87'
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.RangeControl, {
                  label: "Button Border Radius",
                  value: isNormal ? attributes.buttonBorderRadiusNormal : attributes.buttonBorderRadiusHover,
                  onChange: value => setAttributes({
                    [isNormal ? 'buttonBorderRadiusNormal' : 'buttonBorderRadiusHover']: value
                  }),
                  min: 0,
                  max: 50
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
                  label: "Button Padding",
                  value: isNormal ? attributes.buttonPaddingNormal : attributes.buttonPaddingHover,
                  onChange: value => setAttributes({
                    [isNormal ? 'buttonPaddingNormal' : 'buttonPaddingHover']: value
                  }),
                  placeholder: "e.g., 10px 20px"
                })]
              });
            }
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
        dangerouslySetInnerHTML: {
          __html: shortcodeOutput
        }
      })]
    });
  },
  save: () => {
    return null; // Server-side rendering
  }
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map