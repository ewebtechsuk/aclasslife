export default function AdminHome() {
  return (
    <section className="space-y-4">
      <h1 className="text-3xl font-semibold">Admin workspace</h1>
      <p className="text-stone-300">Manage listings, vendors and deal pipelines in one place.</p>
      <a href="/pipeline" className="inline-flex rounded-full bg-white px-5 py-2 text-sm font-semibold text-stone-900">
        View pipeline
      </a>
    </section>
  );
}
