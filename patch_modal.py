from pathlib import Path
import re

base = Path(r'c:\laragon\www\inventaris-stikubank')
list_path = base / 'resources' / 'views' / 'barang' / 'list.blade.php'
text = list_path.read_text(encoding='utf-8')

old_open_detail_start = 'async function openDetail(id){'
old_open_detail_end = 'async function openRemoteForm('
start = text.index(old_open_detail_start)
end = text.index(old_open_detail_end, start)
old_open_detail = text[start:end]

new_open_detail = '''async function openDetail(id){
  openBarangModal('Detail Barang');
  loading();
  try {
    const d = await itemData(id),
          i = d.item,
          rows = d.locations || [],
          locationList = rows.slice(0, 4).map(l => `<li class="flex justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm"><span>${esc(l.name)}</span><strong class="text-slate-700">${l.quantity} ${esc(i.unit)}</strong></li>`).join('') || '<li class="text-sm text-gray-500">Belum ada lokasi.</li>',
          moreLocations = rows.length > 4 ? `<p class="mt-2 text-xs text-gray-400">+${rows.length - 4} lokasi lain</p>` : '';

    modalBody.innerHTML = `
      <div class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-gray-400">Kode Barang</p>
            <p class="font-semibold text-slate-900">${esc(i.item_code)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-gray-400">Kategori</p>
            <p class="font-semibold text-slate-900">${esc(i.category)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-gray-400">Satuan</p>
            <p class="font-semibold text-slate-900">${esc(i.unit)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-gray-400">Minimum Stock</p>
            <p class="font-semibold text-slate-900">${i.minimum_stock ?? '-'}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-gray-400">Nama Barang</p>
            <p class="font-semibold text-slate-900">${esc(i.name)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-gray-400">Status / Kondisi</p>
            <div class="mt-2 flex flex-wrap gap-2">
              <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">${esc(i.status)}</span>
              <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">${esc(i.condition)}</span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-gray-400">Merk / Model</p>
            <p class="font-semibold text-slate-900">${esc(i.brand)} / ${esc(i.model)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-gray-400">Nomor Seri</p>
            <p class="font-semibold text-slate-900">${esc(i.serial_number)}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <h4 class="text-sm font-semibold text-slate-800 mb-2">Spesifikasi</h4>
            <p class="whitespace-pre-line text-sm text-slate-700">${esc(i.specification)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <h4 class="text-sm font-semibold text-slate-800 mb-2">Deskripsi</h4>
            <p class="whitespace-pre-line text-sm text-slate-700">${esc(i.description)}</p>
          </div>
        </div>

        ${i.purchase_receipt_url ? `<a class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white" target="_blank" href="${i.purchase_receipt_url}"><i class="fas fa-file-invoice"></i>Lihat Nota</a>` : ''}

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="flex items-center justify-between gap-3">
            <h4 class="text-sm font-semibold text-slate-800">Distribusi Lokasi</h4>
            ${moreLocations}
          </div>
          <ul class="mt-3 space-y-2">${locationList}</ul>
        </div>
      </div>`;
  } catch {
    modalBody.innerHTML = '<p class="py-10 text-center text-red-600">Detail tidak dapat dimuat.</p>';
  }
}
'''

if old_open_detail not in text:
    raise SystemExit('openDetail block not found exactly')
text = text.replace(old_open_detail, new_open_detail, 1)

old_open_action_start = 'async function openAction(id,type){'
old_open_action_end = 'async function confirmDelete('
start = text.index(old_open_action_start)
end = text.index(old_open_action_end, start)
old_open_action = text[start:end]

new_open_action = '''async function openAction(id,type){
  openBarangModal({add:'Tambah Stock',take:'Gunakan Barang',transfer:'Transfer Lokasi',borrow:'Pinjam Barang'}[type]||'Aksi Barang');
  loading();
  try {
    const d = await itemData(id),
          i = d.item,
          stock = d.stock,
          available = locationOptions(d.locations,true),
          all = locationOptions(d.available_locations);
    let fields = '';
    if(type==='add'){
      fields = `<div class="grid gap-4 sm:grid-cols-2"><div><label class="block text-sm font-medium text-slate-700">Lokasi</label><select required name="location_id" class="field"><option value="">Pilih Lokasi</option>${all}</select></div><div><label class="block text-sm font-medium text-slate-700">Jumlah</label><input required type="number" min="1" max="${stock}" name="quantity" class="field"></div></div>`;
    }
    if(type==='take'){
      fields = `<div class="grid gap-4 sm:grid-cols-2"><div><label class="block text-sm font-medium text-slate-700">Lokasi asal</label><select name="location_id" class="field"><option value="">Semua lokasi</option>${available}</select></div><div><label class="block text-sm font-medium text-slate-700">Jumlah</label><input required type="number" min="1" max="${stock}" name="quantity" class="field"></div></div>`;
    }
    if(type==='transfer'){
      fields = `<div class="grid gap-4"><div class="grid gap-4 sm:grid-cols-2"><div><label class="block text-sm font-medium text-slate-700">Lokasi asal</label><select required name="from_location_id" class="field"><option value="">Pilih Lokasi</option>${available}</select></div><div><label class="block text-sm font-medium text-slate-700">Lokasi tujuan</label><select required name="to_location_id" class="field"><option value="">Pilih Lokasi</option>${all}</select></div></div><div><label class="block text-sm font-medium text-slate-700">Jumlah</label><input required type="number" min="1" max="${stock}" name="quantity" class="field"></div></div>`;
    }
    if(type==='borrow'){
      fields = `<div class="grid gap-4"><div class="rounded-2xl border border-slate-200 bg-white p-4 text-sm"><p class="font-semibold text-slate-900">${esc(i.item_code)} — ${esc(i.name)}</p><p class="mt-1 text-slate-500">Stok tersedia: ${stock} ${esc(i.unit)}</p></div><div class="grid gap-4"><div><label class="block text-sm font-medium text-slate-700">Nama Peminjam</label><input required name="borrower_name" class="field"></div><div><label class="block text-sm font-medium text-slate-700">Nomor Telepon</label><input required name="borrower_phone" type="tel" class="field"></div><div><label class="block text-sm font-medium text-slate-700">Unit / Prodi</label><input name="borrower_unit" class="field"></div><div><label class="block text-sm font-medium text-slate-700">Keperluan</label><textarea required name="purpose" rows="3" class="field"></textarea></div><div><label class="block text-sm font-medium text-slate-700">Tanggal kembali</label><input required type="date" min="{{ now()->addDay()->format('Y-m-d') }}" name="return_date" class="field"></div><div><label class="block text-sm font-medium text-slate-700">Jumlah</label><input required type="number" min="1" max="${stock}" name="quantity" class="field"></div></div></div>`;
    }
    modalBody.innerHTML = `<form action="${endpoint[type]}" method="POST" class="space-y-4"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="consumable_id" value="${id}"><div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700"><p class="font-semibold text-slate-900">${esc(i.item_code)} — ${esc(i.name)}</p><p class="mt-1">Stok tersedia: <span class="font-semibold text-slate-900">${stock} ${esc(i.unit)}</span></p></div>${fields}</div><button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Simpan</button></form>`;
    bindRemoteForm(modalBody.querySelector('form'));
  } catch {
    modalBody.innerHTML = '<p class="py-10 text-center text-red-600">Data barang tidak dapat dimuat.</p>';
  }
}
'''

if old_open_action not in text:
    raise SystemExit('openAction block not found exactly')
text = text.replace(old_open_action, new_open_action, 1)

list_path.write_text(text, encoding='utf-8')
print('patched')
