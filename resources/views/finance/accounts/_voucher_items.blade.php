{{--
  Shared voucher items partial — included by v_cp_items, v_cr_items, v_bp_items, v_br_items, v_jv_items
  Variables expected: $master, $items, $gslList, $depts, $counts, $serialNo, $voucherConfig
  $voucherConfig = [
      'type'        => 'CPV',        // voucher type string
      'label'       => 'Cash Payment Voucher',
      'color'       => 'red',        // tailwind color name
      'debitLabel'  => 'Expense GSL (Debit)',
      'creditLabel' => 'Cash/Bank (Credit)',
      'autoGslCode' => 2001000,      // 0 = no auto entry (JV)
      'autoGslName' => 'Cash in Hand',
      'autoSide'    => 'credit',     // which side auto-entry goes
      'itemsRoute'  => 'accounts.cpv.items',
      'submitRoute' => 'accounts.cpv',
  ]
--}}

@php
  $cfg      = $voucherConfig;
  $color    = $cfg['color'];
  $diff     = abs($items->sum('Debit') - $items->sum('Credit'));
  $editMode = $editMode ?? false;
@endphp

<div class="bg-white rounded shadow-sm p-6">

  {{-- Flash --}}
  @if(session('success'))
  <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">{{ session('success') }}</div>
  @endif

  {{-- Header --}}
  <div class="flex items-center justify-between mb-5">
    <div>
      <h2 class="text-xl font-semibold text-gray-800">
        <i class="fas fa-list text-{{ $color }}-500 mr-2"></i>{{ $cfg['label'] }} — Line Items
      </h2>
      <p class="text-sm text-gray-400 mt-0.5">
        Ref: <strong>{{ $master->RefNo ?? '' }}</strong> &nbsp;|&nbsp;
        Date: <strong>{{ $master->VoucherDate ?? '' }}</strong> &nbsp;|&nbsp;
        Book: <strong>{{ $master->BookNo ?? '' }}</strong>
        @if($master->Payee) &nbsp;|&nbsp; Payee: <strong>{{ $master->Payee }}</strong> @endif
      </p>
    </div>
    <span class="px-3 py-1 bg-{{ $color }}-100 text-{{ $color }}-700 rounded-full text-xs font-bold">
      {{ $cfg['type'] }}
    </span>
  </div>

  {{-- ── Add Line Item Form ───────────────────────────────────────────────── --}}
  @if(!$editMode || is_null($master->A_T) || $master->A_T === 'Reopened')
  <form method="POST" action="{{ route($cfg['itemsRoute'], ['serial_number' => $serialNo]) }}"
        class="mb-6 p-4 bg-gray-50 rounded border border-gray-200" id="addLineForm">
    @csrf
    <input type="hidden" name="serial_number" value="{{ $serialNo }}">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">

      {{-- GSL live-search input --}}
      <div class="md:col-span-1 relative" id="gslWrapper">
        <label class="block text-xs font-medium text-gray-600 mb-1">
          {{ $cfg['debitLabel'] }} <span class="text-red-500">*</span>
        </label>
        <input type="text" id="gslSearchInput" autocomplete="off"
               placeholder="Type code or name…"
               class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-2 focus:ring-{{ $color }}-400">
        <input type="hidden" name="GSL_code" id="gslCodeHidden" required>
        <div id="gslDropdown"
             class="hidden absolute z-50 bg-white border border-gray-300 rounded shadow-lg w-full max-h-52 overflow-y-auto text-sm">
        </div>
        <p class="text-xs text-gray-400 mt-0.5">Use ↑↓ arrows + Enter to select</p>
      </div>

      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Description / Narration</label>
        <input type="text" name="Description" id="descInput" placeholder="Narration"
               class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
      </div>

      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Department</label>
        <select name="Department" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
          <option value="0">-- Dept --</option>
          @foreach($depts as $d)
          <option value="{{ $d->Code }}">{{ $d->Department }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      {{-- For CPV/BPV: user enters Debit; CRV/BRV: user enters Credit; JV: both --}}
      @if(in_array($cfg['type'], ['CPV','BPV']))
      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Amount (Debit)</label>
        <input type="number" name="Debit" id="amtInput" step="0.01" value="0" min="0"
               class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm font-mono">
        <input type="hidden" name="Credit" value="0">
      </div>
      @elseif(in_array($cfg['type'], ['CRV','BRV']))
      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Amount (Credit)</label>
        <input type="number" name="Credit" id="amtInput" step="0.01" value="0" min="0"
               class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm font-mono">
        <input type="hidden" name="Debit" value="0">
      </div>
      @else
      {{-- JV: both fields --}}
      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Debit</label>
        <input type="number" name="Debit" step="0.01" value="0" min="0"
               class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm font-mono">
      </div>
      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Credit</label>
        <input type="number" name="Credit" step="0.01" value="0" min="0"
               class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm font-mono">
      </div>
      @endif

      @if($cfg['autoGslCode'])
      <div class="flex items-end">
        <div class="bg-blue-50 border border-blue-200 rounded px-3 py-1.5 text-xs text-blue-700 w-full">
          <i class="fas fa-magic mr-1"></i>
          Auto-entry: <strong>{{ $cfg['autoGslName'] }}</strong> ({{ $cfg['autoGslCode'] }})
          will be added as <strong>{{ strtoupper($cfg['autoSide']) }}</strong> automatically.
        </div>
      </div>
      @endif

      <div class="flex items-end">
        <button type="submit"
                class="w-full px-4 py-1.5 bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white rounded text-sm font-medium">
          <i class="fas fa-plus mr-1"></i>Add Line
        </button>
      </div>
    </div>
  </form>
  @endif

  {{-- ── Items Table with Inline Edit ───────────────────────────────────────── --}}
  <div class="overflow-x-auto mb-5">
    <table class="min-w-full divide-y divide-gray-200 text-sm" id="itemsTable">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-3 py-2 text-left text-xs text-gray-600 uppercase">#</th>
          <th class="px-3 py-2 text-left text-xs text-gray-600 uppercase">GSL Code</th>
          <th class="px-3 py-2 text-left text-xs text-gray-600 uppercase">GSL Name</th>
          <th class="px-3 py-2 text-left text-xs text-gray-600 uppercase">Description</th>
          <th class="px-3 py-2 text-left text-xs text-gray-600 uppercase">Dept</th>
          <th class="px-3 py-2 text-right text-xs text-gray-600 uppercase">Debit</th>
          <th class="px-3 py-2 text-right text-xs text-gray-600 uppercase">Credit</th>
          @if(is_null($master->A_T) || $master->A_T === 'Reopened')
          <th class="px-3 py-2 text-center text-xs text-gray-600 uppercase">Actions</th>
          @endif
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100" id="itemsTbody">
        @forelse($items as $i => $item)
        @php
          $gslName = $gslList->firstWhere('GSL_code', $item->GSL_code)?->GSL_name ?? '';
          $deptName = $depts->first(fn($d) => (string)$d->Code === (string)$item->Department)?->Department ?? ($item->Department ?: '—');
          $isAuto = ($item->mType == 2);
        @endphp

        {{-- View row --}}
        <tr class="hover:bg-gray-50 {{ $isAuto ? 'bg-blue-50' : '' }}" id="view_row_{{ $item->chld_vch_id }}">
          <td class="px-3 py-2 text-gray-400">
            {{ $i+1 }}
            @if($isAuto)<span class="text-xs text-blue-500 ml-1">auto</span>@endif
          </td>
          <td class="px-3 py-2 font-mono font-bold text-{{ $color }}-700">{{ $item->GSL_code }}</td>
          <td class="px-3 py-2 text-xs text-gray-600">{{ $gslName }}</td>
          <td class="px-3 py-2 text-xs">{{ $item->Description }}</td>
          <td class="px-3 py-2 text-xs text-gray-500">{{ $deptName }}</td>
          <td class="px-3 py-2 font-mono text-right text-red-600">
            {{ $item->Debit ? number_format($item->Debit,2) : '' }}
          </td>
          <td class="px-3 py-2 font-mono text-right text-green-600">
            {{ $item->Credit ? number_format($item->Credit,2) : '' }}
          </td>
          @if(is_null($master->A_T) || $master->A_T === 'Reopened')
          <td class="px-3 py-2 text-center">
            <div class="flex gap-1 justify-center">
              <button type="button"
                      onclick="toggleEdit({{ $item->chld_vch_id }})"
                      class="px-2 py-1 bg-yellow-400 hover:bg-yellow-500 text-white rounded text-xs">
                <i class="fas fa-edit"></i>
              </button>
              <form method="POST"
                    action="{{ $editMode ? route('accounts.voucher.edit.post', $serialNo) : route($cfg['itemsRoute'], ['serial_number' => $serialNo]) }}"
                    onsubmit="return confirm('Delete this line?')">
                @csrf
                <input type="hidden" name="serial_number" value="{{ $serialNo }}">
                <input type="hidden" name="delete_line" value="{{ $item->chld_vch_id }}">
                <button type="submit" class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
          @endif
        </tr>

        {{-- Inline edit row (hidden by default) --}}
        @if(is_null($master->A_T) || $master->A_T === 'Reopened')
        <tr id="edit_row_{{ $item->chld_vch_id }}" class="hidden bg-yellow-50">
          <td colspan="8" class="px-3 py-3">
            <form method="POST"
                  action="{{ $editMode ? route('accounts.voucher.edit.post', $serialNo) : route($cfg['itemsRoute'], ['serial_number' => $serialNo]) }}"
                  class="grid grid-cols-2 md:grid-cols-6 gap-2 items-end">
              @csrf
              <input type="hidden" name="serial_number" value="{{ $serialNo }}">
              <input type="hidden" name="update_line" value="{{ $item->chld_vch_id }}">

              <div>
                <label class="block text-xs text-gray-500 mb-1">GSL Code</label>
                <input type="number" name="edit_GSL_code" value="{{ $item->GSL_code }}" required
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm font-mono">
              </div>
              <div class="md:col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Description</label>
                <input type="text" name="edit_Description" value="{{ $item->Description }}"
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
              </div>
              <div>
                <label class="block text-xs text-gray-500 mb-1">Debit</label>
                <input type="number" name="edit_Debit" step="0.01" value="{{ $item->Debit }}"
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm font-mono">
              </div>
              <div>
                <label class="block text-xs text-gray-500 mb-1">Credit</label>
                <input type="number" name="edit_Credit" step="0.01" value="{{ $item->Credit }}"
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm font-mono">
              </div>
              <div>
                <label class="block text-xs text-gray-500 mb-1">Department</label>
                <select name="edit_Department" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                  <option value="0">-- Dept --</option>
                  @foreach($depts as $d)
                  <option value="{{ $d->Code }}" {{ (string)$d->Code === (string)$item->Department ? 'selected' : '' }}>{{ $d->Department }}</option>
                  @endforeach
                </select>
              </div>
              <div class="flex gap-1">
                <button type="submit"
                        class="flex-1 px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                  <i class="fas fa-save mr-1"></i>Save
                </button>
                <button type="button" onclick="toggleEdit({{ $item->chld_vch_id }})"
                        class="px-2 py-1 bg-gray-400 text-white rounded text-xs hover:bg-gray-500">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </form>
          </td>
        </tr>
        @endif
        @empty
        <tr>
          <td colspan="8" class="px-4 py-6 text-center text-gray-400">
            No line items yet. Add one above.
          </td>
        </tr>
        @endforelse
      </tbody>
      <tfoot class="bg-gray-50 font-bold">
        <tr>
          <td colspan="5" class="px-3 py-2 text-right text-sm text-gray-700">Totals:</td>
          <td class="px-3 py-2 font-mono text-right text-red-600">{{ number_format($items->sum('Debit'),2) }}</td>
          <td class="px-3 py-2 font-mono text-right text-green-600">{{ number_format($items->sum('Credit'),2) }}</td>
          @if(is_null($master->A_T) || $master->A_T === 'Reopened')
          <td></td>
          @endif
        </tr>
      </tfoot>
    </table>
  </div>

  {{-- Balance indicator --}}
  @if(count($items) > 0)
  <div class="mb-4 p-3 rounded text-sm border
      {{ $diff == 0 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-yellow-50 text-yellow-700 border-yellow-200' }}">
    @if($diff == 0)
      <i class="fas fa-check-circle mr-2"></i>Balanced — Debit equals Credit.
    @else
      <i class="fas fa-exclamation-triangle mr-2"></i>
      Unbalanced by <strong>Rs {{ number_format($diff,2) }}</strong> — please balance before submitting.
    @endif
  </div>
  @endif

  {{-- Submit for authentication --}}
  @if(is_null($master->A_T) || $master->A_T === 'Reopened')
  <form method="POST"
        action="{{ $editMode ? route('accounts.voucher.edit.post', $serialNo) : route($cfg['itemsRoute'], ['serial_number' => $serialNo]) }}"
        onsubmit="return confirm('Submit this voucher for authentication?')">
    @csrf
    <input type="hidden" name="serial_number" value="{{ $serialNo }}">
    <input type="hidden" name="Submitit" value="{{ $serialNo }}">
    <button type="submit"
            {{ (count($items) == 0 || $diff != 0) ? 'disabled' : '' }}
            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-medium text-sm
                   disabled:opacity-40 disabled:cursor-not-allowed">
      <i class="fas fa-paper-plane mr-2"></i>Submit for Authentication
    </button>
  </form>
  @elseif($master->A_T === 'Forward')
  <div class="p-3 bg-yellow-50 border border-yellow-300 rounded text-sm text-yellow-800">
    <i class="fas fa-clock mr-2"></i>This voucher is awaiting authentication. It cannot be edited.
  </div>
  @elseif($master->A_T === 'Yes')
  <div class="p-3 bg-green-50 border border-green-300 rounded text-sm text-green-800">
    <i class="fas fa-check-circle mr-2"></i>This voucher is authenticated by <strong>{{ $master->Authenticate }}</strong>.
  </div>
  @endif

</div>

@push('scripts')
<script>
// ── Toggle inline edit row ──────────────────────────────────────────────────
function toggleEdit(id) {
    document.getElementById('view_row_' + id).classList.toggle('hidden');
    document.getElementById('edit_row_' + id).classList.toggle('hidden');
}

// ── Live GSL search with keyboard navigation ────────────────────────────────
(function() {
    const searchInput  = document.getElementById('gslSearchInput');
    const hiddenInput  = document.getElementById('gslCodeHidden');
    const dropdown     = document.getElementById('gslDropdown');
    if (!searchInput) return;

    let activeIdx = -1;
    let results   = [];
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const q = this.value.trim();
        if (q.length < 1) { closeDropdown(); return; }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('accounts.gsl-search') }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    results   = data;
                    activeIdx = -1;
                    renderDropdown();
                });
        }, 200);
    });

    function renderDropdown() {
        dropdown.innerHTML = '';
        if (results.length === 0) { closeDropdown(); return; }
        results.forEach((item, idx) => {
            const div = document.createElement('div');
            div.className = 'px-3 py-2 cursor-pointer hover:bg-blue-50 border-b last:border-0';
            div.innerHTML = `<span class="font-mono font-bold text-purple-700">${item.GSL_code}</span>
                             <span class="ml-2 text-gray-700">${item.GSL_name}</span>`;
            div.addEventListener('mousedown', (e) => {
                e.preventDefault();
                selectItem(idx);
            });
            dropdown.appendChild(div);
        });
        dropdown.classList.remove('hidden');
        highlightItem();
    }

    function highlightItem() {
        Array.from(dropdown.children).forEach((el, i) => {
            el.classList.toggle('bg-blue-100', i === activeIdx);
        });
    }

    function selectItem(idx) {
        const item = results[idx];
        if (!item) return;
        searchInput.value  = item.GSL_code + ' – ' + item.GSL_name;
        hiddenInput.value  = item.GSL_code;
        closeDropdown();
        // Move focus to description
        const descInput = document.getElementById('descInput');
        if (descInput) descInput.focus();
    }

    function closeDropdown() {
        dropdown.classList.add('hidden');
        activeIdx = -1;
    }

    searchInput.addEventListener('keydown', function(e) {
        if (dropdown.classList.contains('hidden')) return;
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIdx = Math.min(activeIdx + 1, results.length - 1);
            highlightItem();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIdx = Math.max(activeIdx - 1, 0);
            highlightItem();
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIdx >= 0) selectItem(activeIdx);
            else if (results.length === 1) selectItem(0);
        } else if (e.key === 'Escape') {
            closeDropdown();
        }
    });

    document.addEventListener('click', function(e) {
        if (!document.getElementById('gslWrapper').contains(e.target)) closeDropdown();
    });

    // Tab key moves to next field after selecting
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && hiddenInput.value) {
            closeDropdown();
        }
    });
})();
</script>
@endpush
