<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Connexion Admin - FDK Signals - Bot manager</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center" style="background:#0a0a0a">
<div class="w-full max-w-sm">
  <div class="text-center mb-8">
    <p class="text-yellow-400 font-bold tracking-widest text-xs uppercase">FDK Signals Bot Manager</p>
    <h1 class="text-white text-2xl font-semibold mt-2">Administration</h1>
  </div>

  <form method="POST" action="{{ route('admin.login') }}" class="bg-[#111511] border border-white/10 rounded-2xl p-8 space-y-5">
    @csrf

    @if($errors->any())
      <p class="text-red-400 text-sm text-center">{{ $errors->first() }}</p>
    @endif

    <div>
      <label class="block text-xs text-gray-400 mb-1.5">Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required
        class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-yellow-400/50">
    </div>

    <div>
      <label class="block text-xs text-gray-400 mb-1.5">Mot de passe</label>
      <input type="password" name="password" required
        class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-yellow-400/50">
    </div>

    <label class="flex items-center gap-2 text-xs text-gray-400 cursor-pointer">
      <input type="checkbox" name="remember" class="accent-yellow-400">
      Se souvenir de moi
    </label>

    <button type="submit"
      class="w-full bg-yellow-400 text-black font-semibold text-sm rounded-lg py-3 hover:bg-yellow-300 transition">
      Se connecter
    </button>
  </form>
</div>
</body>
</html>
