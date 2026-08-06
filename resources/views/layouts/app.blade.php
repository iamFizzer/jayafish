<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>@yield('title','Dashboard') · RR Jaya Fishing</title>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="bg-slate-50 text-slate-800">
<div class="min-h-screen lg:flex">
 <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-40 w-72 -translate-x-full lg:translate-x-0">
  <div class="flex h-20 items-center gap-3 px-6"><div class="brand-mark">RR</div><div><div class="font-display text-lg text-white">RR Jaya Fishing</div><div class="text-xs text-teal-200">Sales Intelligence</div></div></div>
  <nav class="mt-4 space-y-1 px-4">
   <a class="nav-link {{request()->routeIs('dashboard')?'active':''}}" href="{{route('dashboard')}}"><span>⌂</span> Dashboard</a>
   @if(in_array(auth()->user()->role,['admin','karyawan']))
   <div class="nav-label">Operasional</div><a class="nav-link {{request()->routeIs('products.*')?'active':''}}" href="{{route('products.index')}}"><span>◫</span> Produk & Kategori</a><a class="nav-link {{request()->routeIs('stocks.*')?'active':''}}" href="{{route('stocks.index')}}"><span>↓</span> Stok Masuk</a><a class="nav-link {{request()->routeIs('transactions.*')?'active':''}}" href="{{route('transactions.index')}}"><span>↗</span> Transaksi</a>
   @endif
   @if(in_array(auth()->user()->role,['admin','owner']))<div class="nav-label">Analitik</div><a class="nav-link {{request()->routeIs('reports.*')?'active':''}}" href="{{route('reports.index')}}"><span>▥</span> Laporan</a>@endif
   @if(auth()->user()->isAdmin())<div class="nav-label">Sistem</div><a class="nav-link {{request()->routeIs('users.*')?'active':''}}" href="{{route('users.index')}}"><span>◎</span> Pengguna & Aktivitas</a>@endif
  </nav>
  <div class="absolute bottom-5 left-4 right-4 rounded-2xl bg-white/10 p-4"><div class="text-sm font-semibold text-white">{{auth()->user()->name}}</div><div class="mb-3 text-xs capitalize text-teal-200">{{auth()->user()->role}}</div><form method="POST" action="{{route('logout')}}">@csrf<button class="text-xs font-semibold text-white/80 hover:text-white">Keluar dari sistem →</button></form></div>
 </aside>
 <div id="overlay" class="fixed inset-0 z-30 hidden bg-slate-950/50 lg:hidden"></div>
 <main class="min-w-0 flex-1 lg:ml-72"><header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200/70 bg-white/90 px-4 backdrop-blur lg:px-8"><div class="flex items-center gap-3"><button id="menuButton" class="btn-icon lg:hidden">☰</button><div><h1 class="font-display text-xl text-slate-900 lg:text-2xl">@yield('title','Dashboard')</h1><p class="hidden text-sm text-slate-500 sm:block">@yield('subtitle','Pantau performa toko hari ini')</p></div></div><div class="flex items-center gap-3"><div class="hidden text-right sm:block"><div class="text-sm font-semibold">{{auth()->user()->name}}</div><div class="text-xs capitalize text-slate-500">{{auth()->user()->role}}</div></div><div class="avatar">{{strtoupper(substr(auth()->user()->name,0,1))}}</div></div></header>
  <div class="p-4 lg:p-8">@if(session('success'))<div class="alert-success">✓ {{session('success')}}</div>@endif @if($errors->any())<div class="alert-error"><b>Periksa kembali data:</b><ul class="ml-5 mt-1 list-disc">@foreach($errors->all() as $error)<li>{{$error}}</li>@endforeach</ul></div>@endif @yield('content')</div>
 </main>
</div>
@stack('scripts')</body></html>
