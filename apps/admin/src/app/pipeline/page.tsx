const stages = [
  { stage: 'LEAD', count: 8 },
  { stage: 'QUALIFIED', count: 4 },
  { stage: 'NEGOTIATION', count: 3 },
  { stage: 'CLOSED_WON', count: 2 }
];

export default function PipelinePage() {
  return (
    <section className="space-y-8">
      <header>
        <h1 className="text-3xl font-semibold">Deal pipeline</h1>
        <p className="text-stone-300">Track deals and move stages quickly.</p>
      </header>
      <div className="grid gap-4 md:grid-cols-4">
        {stages.map((stage) => (
          <article key={stage.stage} className="rounded-2xl border border-stone-800 p-5">
            <p className="text-xs uppercase tracking-[0.2em] text-stone-400">{stage.stage}</p>
            <p className="mt-3 text-2xl font-semibold">{stage.count}</p>
          </article>
        ))}
      </div>
    </section>
  );
}
