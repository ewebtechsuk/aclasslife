export default function PortalHome() {
  return (
    <section className="space-y-4">
      <h1 className="text-3xl font-semibold">Welcome to your portal</h1>
      <p className="text-slate-300">Manage listings, enquiries and live deals in one place.</p>
      <a href="/dashboard" className="inline-flex rounded-full bg-white px-5 py-2 text-sm font-semibold text-slate-900">
        Go to dashboard
      </a>
    </section>
  );
}
