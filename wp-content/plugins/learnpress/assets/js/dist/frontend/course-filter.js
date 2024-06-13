/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/api.js":
/*!******************************!*\
  !*** ./assets/src/js/api.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * List API on backend
 */

const lplistAPI = {};
if ('undefined' !== typeof lpDataAdmin) {
  lplistAPI.admin = {
    apiAdminNotice: lpDataAdmin.lp_rest_url + 'lp/v1/admin/tools/admin-notices',
    apiAdminOrderStatic: lpDataAdmin.lp_rest_url + 'lp/v1/orders/statistic',
    apiAddons: lpDataAdmin.lp_rest_url + 'lp/v1/addon/all',
    apiAddonAction: lpDataAdmin.lp_rest_url + 'lp/v1/addon/action',
    apiSearchCourses: lpDataAdmin.lp_rest_url + 'lp/v1/admin/tools/search-course',
    apiSearchUsers: lpDataAdmin.lp_rest_url + 'lp/v1/admin/tools/search-user',
    apiAssignUserCourse: lpDataAdmin.lp_rest_url + 'lp/v1/admin/tools/assign-user-course',
    apiUnAssignUserCourse: lpDataAdmin.lp_rest_url + 'lp/v1/admin/tools/unassign-user-course'
  };
}
if ('undefined' !== typeof lpData) {
  lplistAPI.frontend = {
    apiWidgets: lpData.lp_rest_url + 'lp/v1/widgets/api',
    apiCourses: lpData.lp_rest_url + 'lp/v1/courses/archive-course',
    apiAJAX: lpData.lp_rest_url + 'lp/v1/load_content_via_ajax/'
  };
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (lplistAPI);

/***/ }),

/***/ "./assets/src/js/utils.js":
/*!********************************!*\
  !*** ./assets/src/js/utils.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   listenElementCreated: () => (/* binding */ listenElementCreated),
/* harmony export */   listenElementViewed: () => (/* binding */ listenElementViewed),
/* harmony export */   lpAddQueryArgs: () => (/* binding */ lpAddQueryArgs),
/* harmony export */   lpFetchAPI: () => (/* binding */ lpFetchAPI),
/* harmony export */   lpGetCurrentURLNoParam: () => (/* binding */ lpGetCurrentURLNoParam)
/* harmony export */ });
/**
 * Fetch API.
 *
 * @param url
 * @param data
 * @param functions
 * @since 4.2.5.1
 */
const lpFetchAPI = (url, data = {}, functions = {}) => {
  if ('function' === typeof functions.before) {
    functions.before();
  }
  fetch(url, {
    method: 'GET',
    ...data
  }).then(response => response.json()).then(response => {
    if ('function' === typeof functions.success) {
      functions.success(response);
    }
  }).catch(err => {
    if ('function' === typeof functions.error) {
      functions.error(err);
    }
  }).finally(() => {
    if ('function' === typeof functions.completed) {
      functions.completed();
    }
  });
};

/**
 * Get current URL without params.
 *
 * @since 4.2.5.1
 */
const lpGetCurrentURLNoParam = () => {
  let currentUrl = window.location.href;
  const hasParams = currentUrl.includes('?');
  if (hasParams) {
    currentUrl = currentUrl.split('?')[0];
  }
  return currentUrl;
};
const lpAddQueryArgs = (endpoint, args) => {
  const url = new URL(endpoint);
  Object.keys(args).forEach(arg => {
    url.searchParams.set(arg, args[arg]);
  });
  return url;
};

/**
 * Listen element viewed.
 *
 * @param el
 * @param callback
 * @since 4.2.5.8
 */
const listenElementViewed = (el, callback) => {
  const observerSeeItem = new IntersectionObserver(function (entries) {
    for (const entry of entries) {
      if (entry.isIntersecting) {
        callback(entry);
      }
    }
  });
  observerSeeItem.observe(el);
};

/**
 * Listen element created.
 *
 * @param callback
 * @since 4.2.5.8
 */
const listenElementCreated = callback => {
  const observerCreateItem = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.addedNodes) {
        mutation.addedNodes.forEach(function (node) {
          if (node.nodeType === 1) {
            callback(node);
          }
        });
      }
    });
  });
  observerCreateItem.observe(document, {
    childList: true,
    subtree: true
  });
  // End.
};


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
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
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
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*************************************************!*\
  !*** ./assets/src/js/frontend/course-filter.js ***!
  \*************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../api */ "./assets/src/js/api.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils */ "./assets/src/js/utils.js");


const classCourseFilter = 'lp-form-course-filter';

// Events
// Submit form filter
document.addEventListener('submit', function (e) {
  const target = e.target;
  if (!target.classList.contains(classCourseFilter)) {
    return;
  }
  e.preventDefault();
  window.lpCourseFilter.submit(target);
});

// Click element
document.addEventListener('click', function (e) {
  const target = e.target;
  if (target.classList.contains('course-filter-reset')) {
    e.preventDefault();
    window.lpCourseFilter.reset(target);
  }

  // Show/hide search suggest result
  window.lpCourseFilter.showHideSearchResult(target);

  // Click field
  window.lpCourseFilter.triggerInputChoice(target);
});

// Search course suggest
document.addEventListener('keyup', function (e) {
  e.preventDefault();
  const target = e.target;
  if (target.classList.contains('lp-course-filter-search')) {
    window.lpCourseFilter.searchSuggestion(target);
  }
});
let timeOutSearch;
let controller;
let signal;
window.lpCourseFilter = {
  searchSuggestion: inputSearch => {
    const enable = parseInt(inputSearch.dataset.searchSuggest || 1);
    if (1 !== enable) {
      return;
    }
    const keyword = inputSearch.value.trim();
    const form = inputSearch.closest(`.${classCourseFilter}`);
    const elLoading = form.querySelector('.lp-loading-circle');
    if (undefined !== timeOutSearch) {
      clearTimeout(timeOutSearch);
    }
    if (keyword && keyword.length > 2) {
      elLoading.classList.remove('hide');
      timeOutSearch = setTimeout(function () {
        const callBackDone = response => {
          const elResult = document.querySelector('.lp-course-filter-search-result');
          elResult.innerHTML = response.data.content;
          elLoading.classList.add('hide');
        };
        window.lpCourseFilter.callAPICourseSuggest(keyword, callBackDone);
      }, 500);
    } else {
      const elResult = form.querySelector('.lp-course-filter-search-result');
      elResult.innerHTML = '';
      elLoading.classList.add('hide');
    }
  },
  callAPICourseSuggest: (keyword, callBackDone, callBackFinally) => {
    if (undefined !== controller) {
      controller.abort();
    }
    controller = new AbortController();
    signal = controller.signal;
    let url = _api__WEBPACK_IMPORTED_MODULE_0__["default"].frontend.apiCourses + '?c_search=' + keyword + '&c_suggest=1';
    if (lpData.urlParams.hasOwnProperty('lang')) {
      url += '&lang=' + lpData.urlParams.lang;
    }
    let paramsFetch = {
      method: 'GET'
    };
    if (0 !== parseInt(lpData.user_id)) {
      paramsFetch = {
        ...paramsFetch,
        headers: {
          'X-WP-Nonce': lpData.nonce
        }
      };
    }
    fetch(url, {
      ...paramsFetch,
      signal
    }).then(response => response.json()).then(response => {
      if (undefined !== callBackDone) {
        callBackDone(response);
      }
    }).catch(error => {
      console.log(error);
    }).finally(() => {
      if (undefined !== callBackFinally) {
        callBackFinally();
      }
    });
  },
  loadWidgetFilterREST: widgetForm => {
    const parent = widgetForm.closest('.learnpress-widget-wrapper');
    if (!parent) {
      return;
    }
    const widgetData = parent.dataset.widget ? JSON.parse(parent.dataset.widget) : '';
    const url = _api__WEBPACK_IMPORTED_MODULE_0__["default"].frontend.apiWidgets;
    const formData = new FormData(widgetForm);
    const filterCourses = {
      paged: 1
    };
    const elLoadingChange = parent.querySelector('.lp-widget-loading-change');
    elLoadingChange.style.display = 'block';
    for (const pair of formData.entries()) {
      const key = pair[0];
      const value = formData.getAll(key);
      if (!filterCourses.hasOwnProperty(key)) {
        let value_convert = value;
        if ('object' === typeof value) {
          value_convert = value.join(',');
        }
        filterCourses[key] = value_convert;
      }
    }
    if ('undefined' !== typeof lpData.urlParams.page_term_id_current) {
      filterCourses.page_term_id_current = lpData.urlParams.page_term_id_current;
    } else if ('undefined' !== typeof lpData.urlParams.page_tag_id_current) {
      filterCourses.page_tag_id_current = lpData.urlParams.page_tag_id_current;
    }
    const filterParamsUrl = {
      params_url: filterCourses
    };
    // Send lang to API if exist for multiple lang.
    if (lpData.urlParams.hasOwnProperty('lang')) {
      filterParamsUrl.params_url.lang = lpData.urlParams.lang;
    }
    const paramsFetch = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        ...widgetData,
        ...filterParamsUrl
      })
    };
    if (0 !== parseInt(lpData.user_id)) {
      paramsFetch.headers['X-WP-Nonce'] = lpData.nonce;
    }
    const callBack = {
      before: () => {},
      success: res => {
        const {
          data,
          status,
          message
        } = res;
        if (data && status === 'success') {
          widgetForm.innerHTML = data;
        } else if (message) {
          parent.insertAdjacentHTML('afterbegin', `<div class="lp-ajax-message error" style="display:block">${message}</div>`);
        }
      },
      error: error => {},
      completed: () => {
        elLoadingChange.style.display = 'none';
      }
    };

    // Call API load widget
    (0,_utils__WEBPACK_IMPORTED_MODULE_1__.lpFetchAPI)(url, paramsFetch, callBack);
  },
  submit: form => {
    const formData = new FormData(form); // Create a FormData object from the form
    const elListCourse = document.querySelector('.learn-press-courses');
    const idListCourseTarget =  false || '#lp-list-courses-default';
    const elListCourseTarget = document.querySelector(idListCourseTarget);

    //const skeleton = elListCourse.querySelector( '.lp-archive-course-skeleton' );
    const filterCourses = {
      paged: 1
    };
    if ('undefined' !== typeof window.lpCourseList) {
      window.lpCourseList.updateEventTypeBeforeFetch('filter');
    }
    for (const pair of formData.entries()) {
      const key = pair[0];
      const value = formData.getAll(key);
      if (!filterCourses.hasOwnProperty(key)) {
        // Convert value array to string.
        filterCourses[key] = value.join(',');
      }
    }
    if ('undefined' !== typeof lpData.urlParams.page_term_id_current) {
      filterCourses.page_term_id_current = lpData.urlParams.page_term_id_current;
    }
    if ('undefined' !== typeof lpData.urlParams.page_tag_id_current) {
      filterCourses.page_tag_id_current = lpData.urlParams.page_tag_id_current;
    }

    // Send lang to API if exist for multiple lang.
    if (lpData.urlParams.hasOwnProperty('lang')) {
      filterCourses.lang = lpData.urlParams.lang;
    }
    if ('undefined' !== typeof lpSettingCourses &&
    // Old version.
    lpData.is_course_archive && lpSettingCourses.lpArchiveLoadAjax && elListCourse && !elListCourseTarget && 'undefined' !== typeof window.lpCourseList) {
      window.lpCourseList.triggerFetchAPI(filterCourses);
    } else if (elListCourseTarget) {
      const elLPTarget = elListCourseTarget.querySelector('.lp-target');
      const dataObj = JSON.parse(elLPTarget.dataset.send);
      const dataSend = {
        ...dataObj
      };

      // Show loading list courses
      const elLoading = elListCourseTarget.closest('div:not(.lp-target)').querySelector('.lp-loading-change');
      if (elLoading) {
        elLoading.style.display = 'block';
      }
      // End

      // Get all fields in form.
      const fields = form.elements;
      // If field not selected on form, will remove value on dataSend.
      for (let i = 0; i < fields.length; i++) {
        if (!filterCourses.hasOwnProperty(fields[i].name)) {
          delete dataSend.args[fields[i].name];
        } else {
          dataSend.args[fields[i].name] = filterCourses[fields[i].name];
        }
      }
      // End.

      dataSend.args.paged = 1;
      elLPTarget.dataset.send = JSON.stringify(dataSend);

      // Set url params to reload page.
      // Todo: need check allow set url params.
      lpData.urlParams = filterCourses;
      window.history.pushState({}, '', (0,_utils__WEBPACK_IMPORTED_MODULE_1__.lpAddQueryArgs)((0,_utils__WEBPACK_IMPORTED_MODULE_1__.lpGetCurrentURLNoParam)(), lpData.urlParams));
      // End.

      const callBack = {
        success: response => {
          //console.log( 'response', response );
          const {
            status,
            message,
            data
          } = response;
          elLPTarget.innerHTML = data.content || '';
        },
        error: error => {
          console.log(error);
        },
        completed: () => {
          //console.log( 'completed' );
          if (elLoading) {
            elLoading.style.display = 'none';
          }
        }
      };
      window.lpAJAXG.fetchAPI(_api__WEBPACK_IMPORTED_MODULE_0__["default"].frontend.apiAJAX, dataSend, callBack);
    } else {
      const courseUrl = lpData.urlParams.page_term_url || lpData.courses_url || '';
      const url = new URL(courseUrl);
      Object.keys(filterCourses).forEach(arg => {
        url.searchParams.set(arg, filterCourses[arg]);
      });
      document.location.href = url.href;
    }
  },
  reset: btnReset => {
    const form = btnReset.closest(`.${classCourseFilter}`);
    const btnSubmit = form.querySelector('.course-filter-submit');
    const elResult = form.querySelector('.lp-course-filter-search-result');
    const elSearch = form.querySelector('.lp-course-filter-search');
    form.reset();
    if (elResult) {
      elResult.innerHTML = '';
    }
    if (elSearch) {
      elSearch.value = '';
    }
    // Uncheck value with case set default from params url.
    for (let i = 0; i < form.elements.length; i++) {
      form.elements[i].removeAttribute('checked');
    }
    // If on the page archive course will call btnSubmit click.
    if (lpData.is_course_archive) {
      btnSubmit.click();
    }

    // Load AJAX widget by params
    window.lpCourseFilter.loadWidgetFilterREST(form);
  },
  showHideSearchResult: target => {
    const elResult = document.querySelector('.lp-course-filter-search-result');
    if (!elResult) {
      return;
    }
    const parent = target.closest('.lp-course-filter-search-result');
    if (!parent && !target.classList.contains('lp-course-filter-search-result') && !target.classList.contains('lp-course-filter-search')) {
      elResult.style.display = 'none';
    } else {
      elResult.style.display = 'block';
    }
  },
  triggerInputChoice: target => {
    if (target.tagName === 'INPUT') {
      const parent = target.closest('.lp-course-filter__field');
      if (!parent) {
        return;
      }
      const idListCourseTarget =  false || '#lp-list-courses-default';
      const elListCourseTarget = document.querySelector(idListCourseTarget);

      // Filter courses
      const form = parent.closest(`.${classCourseFilter}`);
      const btnSubmit = form.querySelector('.course-filter-submit');
      let enableLoadAJAXCourses = false;
      enableLoadAJAXCourses = 'undefined' !== typeof lpSettingCourses ? parseInt(lpSettingCourses.lpArchiveLoadAjax) : 0;
      const elListCourse = document.querySelector('.learn-press-courses');
      if (elListCourse && enableLoadAJAXCourses) {
        // Old version.
        btnSubmit.click();
      } else if (elListCourseTarget) {
        btnSubmit.click();
      }

      // Load AJAX widget by params
      window.lpCourseFilter.loadWidgetFilterREST(form);
      return;
    }

    // Click el parent of input to tick/untick field
    let elChoice;
    if (target.classList.contains('lp-course-filter__field')) {
      elChoice = target;
    }
    const parent = target.closest('.lp-course-filter__field');
    if (parent) {
      elChoice = parent;
    }
    if (!elChoice) {
      return;
    }
    elChoice.querySelector('input').click();
  }
};
})();

/******/ })()
;
//# sourceMappingURL=course-filter.js.map