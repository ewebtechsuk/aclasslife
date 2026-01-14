export default function NewListingPage() {
  return (
    <section className="space-y-6">
      <header>
        <h1 className="text-3xl font-semibold">Create listing</h1>
        <p className="text-slate-300">Draft a new listing and submit for review.</p>
      </header>
      <div className="rounded-2xl border border-slate-800 p-6">
        <p className="text-sm text-slate-400">Listing editor will capture title, category, pricing and media.</p>
        <button className="mt-6 rounded-full bg-white px-5 py-2 text-sm font-semibold text-slate-900">
          Save draft
        </button>
      </div>
    </section>
  );
}
