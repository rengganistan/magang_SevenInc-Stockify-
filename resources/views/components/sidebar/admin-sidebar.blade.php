<x-sidebar-dashboard>

    <x-sidebar-menu-dashboard
        routeName="admin.dashboard"
        title="Dashboard"/>

    <li class="pt-4">
        <span class="px-3 text-xs font-bold uppercase text-gray-400">
            MASTER DATA
        </span>
    </li>

    <x-sidebar-menu-dashboard
        routeName="users.index"
        title="Users"/>

    <x-sidebar-menu-dashboard
        routeName="categories.index"
        title="Kategori"/>

        <x-sidebar-menu-dashboard
        routeName="suppliers.index"
        title="Supplier"/>

    <x-sidebar-menu-dashboard
        routeName="products.index"
        title="Produk"/>

    <li class="pt-4">
        <span class="px-3 text-xs font-bold uppercase text-gray-400">
            TRANSAKSI
        </span>
    </li>

    <div class="ml-5">

        <x-sidebar-menu-dashboard
            routeName="transactions.incoming"
            title="Barang Masuk"/>

        <x-sidebar-menu-dashboard
            routeName="transactions.outgoing"
            title="Barang Keluar"/>

        <x-sidebar-menu-dashboard
            routeName="stock-opname.index"
            title="Stock Opname"/>

    </div>

    <li class="pt-4">
        <span class="px-3 text-xs font-bold uppercase text-gray-400">
            LAPORAN
        </span>
    </li>

    <div class="ml-5">

        <x-sidebar-menu-dashboard
    routeName="reports.stock"
    title="Laporan Stok"/>

<x-sidebar-menu-dashboard
    routeName="reports.transaction"
    title="Laporan Transaksi"/>

<x-sidebar-menu-dashboard
    routeName="reports.activity"
    title="Aktivitas User"/>

    </div>

    <p class="pt-5 pb-2 text-xs font-semibold text-gray-400 uppercase">
    Pengaturan
</p>

<x-sidebar-menu-dashboard
    routeName="settings.index"
    title="Pengaturan Aplikasi"/>
</x-sidebar-dashboard>
