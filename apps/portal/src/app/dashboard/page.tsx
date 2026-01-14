const metrics = [
  { label: 'Active listings', value: '12' },
  { label: 'Enquiries this week', value: '7' },
  { label: 'Deals in negotiation', value: '3' }
];

export default function DashboardPage() {
  return (
    <section className="space-y-8">
      <div>
        <h1 className="text-3xl font-semibold">Dashboard</h1>
        <p className="text-slate-300">Your current performance snapshot.</p>
      </div>
      <div className="grid gap-4 md:grid-cols-3">
        {metrics.map((metric) => (
          <article key={metric.label} className="rounded-2xl border border-slate-800 p-5">
            <p className="text-xs uppercase tracking-[0.2em] text-slate-400">{metric.label}</p>
            <p className="mt-3 text-2xl font-semibold">{metric.value}</p>
          </article>
        ))}
      </div>
    </section>
  );
}
