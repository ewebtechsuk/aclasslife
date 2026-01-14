const queue = [
  { id: 'vendor-22', name: 'Regency Estates' },
  { id: 'vendor-31', name: 'Chrono Collective' }
];

export default function VendorReviewPage() {
  return (
    <section className="space-y-6">
      <header>
        <h1 className="text-3xl font-semibold">Vendor verification</h1>
        <p className="text-stone-300">Verify vendors before they list publicly.</p>
      </header>
      <div className="space-y-4">
        {queue.map((vendor) => (
          <article key={vendor.id} className="rounded-2xl border border-stone-800 p-5">
            <h2 className="text-lg font-semibold">{vendor.name}</h2>
            <div className="mt-4 flex gap-3">
              <button className="rounded-full bg-emerald-400 px-4 py-2 text-xs font-semibold text-emerald-950">
                Verify
              </button>
              <button className="rounded-full border border-stone-700 px-4 py-2 text-xs font-semibold text-stone-100">
                Unverify
              </button>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}
