$(document).ready(function () {
	activateExpandButton()
	markMinus($('li[data-uuid]'))

	$('.btn-save').click(function () {
		if ($('li[data-uuid]').filter(function () {
		  return $(this).css('background-color') === 'rgb(255, 204, 204)'
		}).length > 0) {
			showError('Formulir gagal dikirim, perhitungan minus')
		} else {
			$('[data-number]').each (function () {
				$(this).val(getNumber($(this)))
			})
			$('form#form_list').submit()
		}
	})
})

function activateExpandButton () {
	$('.expand-btn').unbind('click').bind('click', function () {
		if ($(this).is('.fa-plus-square')) {
			expandItem ($(this), function () {
				activateExpandButton ()
			})
			$(this).removeClass('fa-plus-square').addClass('fa-minus-square')
		} else if ($(this).is('.fa-minus-square')) {
			foldItem ($(this))
			$(this).removeClass('fa-minus-square').addClass('fa-plus-square')
		}
	})
}

function activateBtnDelete () {
	$('.btn-delete').unbind('click').bind('click', function () {
		var li = $(this).parent().parent().parent().parent().parent().parent().parent()
		li.remove()
		calculateBottomUp (li)
	})
}

function activateAddBtn (li) {
	var last = $('[data-parent="' + li.uuid + '"]').last()
	var indent = last.css('padding-left')
	if (last.length < 1) {
		last = $('li[data-uuid="' + li.uuid + '"]')
		indent = parseInt(last.css('padding-left').replace('px', 0))
	}
	var addBtn = '<li class="item" data-uuid="" data-parent="' + li.uuid + '" style="padding-left: ' + indent + '">\
	    <div class="item-row">\
        <div class="item-col">\
					<a class="add-btn btn btn-info"><i class="fa fa-plus"></i> SPJ</a>\
				</div>\
			</div>\
	</li>'
	$(addBtn).css('padding-left', li.indent + 10 + 'px').insertAfter(last).find('.btn').click(function () {
		var btn = $(this)
		$.get(site_url + li.childController + '/subformlistcreate/' + li.uuid, function (form) {
			$(form).insertBefore(btn.parent().parent().parent()).css('padding-left', li.indent + 10 + 'px')
			activateBtnDelete()
			activateRealtimeCalculation()
		})
	})
}

function expandItem (btn, cb) {
	var li = btn.parent().parent().parent().parent()
	var parent = {
		uuid: li.attr('data-uuid'),
		childUuids: li.attr('data-child-uuid').split(','),
		childController: li.attr('data-child-controller'),
		indent: parseInt(li.css('padding-left').replace('px', ''), 10)
	}
	if (parent.childUuids[0].length < 1) cb()
	var requests = []
	for (var uuid of parent.childUuids) {
		var url = site_url + parent.childController + '/subformlist/' + uuid
		if ('Spj' === parent.childController && uuid.length < 1) url = site_url + parent.childController + '/subformlistcreate/' + parent.uuid
		requests.push($.ajax({
			url: url,
			success: function (item) {
				$(item).insertAfter(li)
			}
		}))
	}
	$.when.apply(undefined, requests).then(function () {
		sortItem(li)
		$('[data-parent="' + li.attr('data-uuid') + '"]').css('padding-left', parent.indent + 10 + 'px')
		.each(function () {
			markMinus($(this))
		})
		activateBtnDelete()
		activateRealtimeCalculation()
		if ('Spj' === parent.childController) {
			activateAddBtn(parent)
			activateListVerificationButton(parent)
			adjustPaymentButton(parent)
			activateFormVerificationButton(parent)
		}
		cb()
	})
}

function foldItem (btn) {
	var li = btn.parent().parent().parent().parent()

	var sibling = $('li[data-uuid]').filter(function () {
		return $(this).css('padding-left') === li.css('padding-left') && $(this).index() > li.index()
	}).eq(0)

	$('li[data-uuid]').filter(function () {
		$filterBool = $(this).css('padding-left') > li.css('padding-left') && $(this).index() > li.index()
		return sibling.index() > -1 ? $filterBool * sibling.index() > $(this).index() : $filterBool
	}).remove()
}

function sortItem (li) {
	var elements = $('[data-parent="' + li.attr('data-uuid') + '"]')
	var elems = []
	for( var i = 0; i < elements.length; ++i ) {
		var el = elements[i]
		elems.push(el)
	}
	var sorted = elems.sort(function (a, b) {
		var aValue = parseInt(a.getAttribute('data-urutan'), 10)
		var bValue = parseInt(b.getAttribute('data-urutan'), 10)
		return aValue - bValue
	})

  var html = ''
  $('[data-parent="' + li.attr('data-uuid') + '"]').remove()
  for( var i = 0; i < sorted.length; ++i ) html += sorted[i].outerHTML
  $(html).insertAfter(li)

}

function activateRealtimeCalculation () {
	$('[data-number]').unbind('keyup').bind('keyup', function () {
		var li = $(this).parent().parent().parent().parent().parent().parent().parent()
		var vol= getNumber (li.find('.input-vol'))
		var hargasat = getNumber (li.find('.input-hargasat'))
		var ppn = getNumber (li.find('.input-ppn'))
		var pph = getNumber (li.find('.input-pph'))
		li.find('.total_spj').val(currency (vol * hargasat + ppn + pph))
		$(this).val(currency (getNumber ($(this))))
		calculateBottomUp (li)
	})
}

function calculateBottomUp (li) {
	if (!li.is('[data-parent]')) return true
	else {
		var parentUuid = li.attr('data-parent')
		var parentLi = $('[data-uuid="' + li.attr('data-parent') + '"]')
		var total_spj = 0
		$('[data-parent="' + parentUuid + '"]').each(function () {
			total_spj += $(this).find('.total_spj').length > 0 ? getNumber($(this).find('.total_spj')) : 0
		})
		parentLi.find('.total_spj').html(currency (total_spj))
		markMinus(parentLi)
		calculateBottomUp (parentLi)
	}
}

function getNumber (element) {
  var val = element.val() || element.html()
  val = val.split(',').join('')
  return isNaN(val) || val.length < 1 ? 0 : parseInt (val)
}

function currency (number) {
	var	reverse = number.toString().split('').reverse().join(''),
	currency 	= reverse.match(/\d{1,3}/g)
	return currency.join(',').split('').reverse().join('')
}

function markMinus (li) {
	var inlineStyle = li.attr('style') || ''
	if (li.find('.pagu').length < 1) return true
	if (getNumber(li.find('.total_spj')) > getNumber(li.find('.pagu'))) li.css('background-color', '#ffcccc')
	else li.attr('style', inlineStyle.replace('background-color: rgb(255, 204, 204);', ''))
}

function activateListVerificationButton (parent) {
	$('[data-parent="' + parent.uuid + '"]').each(function () {
		var spj = $(this)
		var statusInput = spj.find('input[type="hidden"][name*="Spj_status"]')
		var status = statusInput.val()
		spj.find('.btn-status').hide()
		spj.find('.btn.' + status).show()
		spj.find('.list-verification-btn').click(function () {
			statusInput.val('verify')
			spj.find('.btn-status').hide()
			spj.find('.btn.verified').show()
		})
		spj.find('.list-unverification-btn').click(function () {
			statusInput.val('unverify')
			spj.find('.btn-status').hide()
			spj.find('.btn.unverified').show()
			var modal = $('#modalDialog')
			var input = modal.find('textarea')
			input.val('')
			modal.modal()
			$('#modalDialog .fa-save').parent().unbind('click').bind('click', function () {
				var reason = input.val()
				spj.find('input[type="hidden"][name*="Spj_unverify_reason"]').val(reason)
			})
		})
	})
}

function adjustPaymentButton (parent) {
	$('[data-parent="' + parent.uuid + '"]').each(function () {
		var spj = $(this)
		spj.find('.btn-payment').hide()
		if ('verified' === spj.find('input[type="hidden"][name*="global_status"]').val()) {
			var paymentStatus= spj.find('input[type="hidden"][name*="payment_status"]').val()
			spj.find('.btn.' + paymentStatus).show()
		}
	})
}

function activateFormVerificationButton (parent) {
	$('.form-unverification-btn, .form-verification-btn').unbind('click').bind('click', function () {
		var formdata = {
			status: $(this).is('.form-verification-btn') ? 'verify' : 'unverify'
		}
		if ('verify' === formdata.status && $('li[data-uuid]').filter(function () {
		  return $(this).css('background-color') === 'rgb(255, 204, 204)'
		}).length > 0) showError('Formulir gagal dikirim, perhitungan minus')
		else {
			var li = $(this).parent().parent().parent().parent().parent().parent().parent()
			li.find('input, textarea').not('[disabled]').each(function () {
				var name = $(this).attr('name').split('[')[1].split(']')[0].replace('Spj_', '')
				formdata[name] = $(this).is('[data-number]') ? getNumber($(this)) : $(this).val()
			})
			$.post(site_url + 'Spj/save', formdata, function () {
				$.ajax({
					url: site_url + 'Spj/subformlist/' + formdata.uuid,
					success: function (item) {
						li.replaceWith(item)
						li = $('[data-uuid="'+formdata.uuid+'"]')
						li.css('padding-left', parent.indent + 10 + 'px')
						markMinus(li)
						activateListVerificationButton(parent)
						adjustPaymentButton(parent)
					}
				})
			})
		}
	})
}