const queue = [
  { id: 'listing-88', title: 'Falcon 8X', vendor: 'Skyline Jets' },
  { id: 'listing-45', title: 'Mediterranean 74m', vendor: 'Azure Yachts' }
];

export default function ListingReviewPage() {
  return (
    <section className="space-y-6">
      <header>
        <h1 className="text-3xl font-semibold">Listing review queue</h1>
        <p className="text-stone-300">Approve or reject submissions from vendors.</p>
      </header>
      <div className="space-y-4">
        {queue.map((item) => (
          <article key={item.id} className="rounded-2xl border border-stone-800 p-5">
            <h2 className="text-lg font-semibold">{item.title}</h2>
            <p className="text-sm text-stone-400">Vendor: {item.vendor}</p>
            <div className="mt-4 flex gap-3">
              <button className="rounded-full bg-emerald-400 px-4 py-2 text-xs font-semibold text-emerald-950">
                Approve
              </button>
              <button className="rounded-full border border-stone-700 px-4 py-2 text-xs font-semibold text-stone-100">
                Reject
              </button>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}
