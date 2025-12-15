import { useState } from "react";
import { ImageWithFallback } from "./figma/ImageWithFallback";

interface Event {
  title: string;
  location: string;
  date: string;
  description: string;
}

export function EventsPage() {
  const [selectedEvent, setSelectedEvent] = useState<Event | null>(null);
  const [formData, setFormData] = useState({
    firstName: "",
    lastName: "",
    email: "",
    phone: ""
  });
  const [showConfirmation, setShowConfirmation] = useState(false);

  const openEvent = (title: string, location: string, date: string, description: string) => {
    setSelectedEvent({ title, location, date, description });
    setShowConfirmation(false);
  };

  const goBack = () => {
    setSelectedEvent(null);
    setShowConfirmation(false);
  };

  const submitForm = (e: React.FormEvent) => {
    e.preventDefault();
    setShowConfirmation(true);
  };

  return (
    <div style={{ marginTop: '80px' }}>
      {/* Event Marquee */}
      <div className="event-marquee">
        <div className="marquee-track">
          <div className="marquee-content">
            ✨ Events Coming Soon in 2026 • New Opportunities — Stay Tuned • Lumina Community Events — 2026 Edition •
          </div>
          <div className="marquee-content">
            ✨ Events Coming Soon in 2026 • New Opportunities — Stay Tuned • Lumina Community Events — 2026 Edition •
          </div>
        </div>
      </div>

      <div className="container">
        {/* Hero Section */}
        <section className="hero-section fade-in visible">
          <div className="hero-left">
            <h1>Welcome to <b>Lumina Events</b> 🎉</h1>
            <p className="hero-sub">
              Discover amazing events, join meaningful missions, and support communities across Tunisia. Every event is an opportunity to make new friends! 💙
            </p>

            <div className="hero-input">
              <input type="email" placeholder="Subscribe for event updates ✉️" />
              <button>Subscribe!</button>
            </div>
          </div>

          <div className="hero-right">
            <ImageWithFallback 
              src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=600&h=600&fit=crop" 
              alt="Lumina Events"
              className="w-full rounded-[18px] border border-[var(--border)] shadow-md"
            />
          </div>
        </section>

        {/* Trending Topics */}
        <section className="trending fade-in visible trending-center">
          <h3>🌟 Trending Topics</h3>
          <div className="topics-scroll">
            <div className="topic-item"><a href="#">Community 🤝</a></div>
            <div className="topic-item"><a href="#">Health 💊</a></div>
            <div className="topic-item"><a href="#">Environment 🌱</a></div>
            <div className="topic-item"><a href="#">Charity 💝</a></div>
            <div className="topic-item"><a href="#">Volunteering ✨</a></div>
          </div>
        </section>

        {/* Layout */}
        <div className="layout">
          {/* Sidebar */}
          <aside className="sidebar fade-in visible">
            <div className="about-card">
              <div style={{ 
                backgroundColor: 'rgba(129, 149, 184, 0.2)', 
                borderRadius: '14px', 
                padding: '2rem', 
                textAlign: 'center',
                marginBottom: '1rem'
              }}>
                <div style={{ fontSize: '3rem' }}>👥</div>
              </div>
              <h4 style={{ margin: '10px 0 4px' }}>Lumina Team 💙</h4>
              <p style={{ color: 'var(--muted)' }}>We organize events across all Tunisian regions with love and dedication!</p>
            </div>

            <div className="tag-card">
              <h4 style={{ margin: '0 0 10px' }}>Popular Tags ✨</h4>
              <div className="tags">
                <span>Health</span>
                <span>Charity</span>
                <span>Environment</span>
                <span>Food Aid</span>
                <span>Education</span>
              </div>
            </div>
          </aside>

          {/* Main Content */}
          <main className="main-content">
            <div id="eventList" className="fade-in visible" style={{ display: selectedEvent ? 'none' : 'block' }}>
              <h2>Available Events 🎊</h2>
              <p style={{ color: 'var(--text)', marginBottom: '1.5rem' }}>
                Find the perfect event for you and start making a difference today!
              </p>
              
              <div className="event-list">
                <div 
                  className="card" 
                  onClick={() => openEvent(
                    'Food Aid Distribution',
                    'Sousse',
                    '20 Feb 2025',
                    'Join us in distributing essential food packs to families in need across Sousse. Every helping hand makes a difference! 🍎'
                  )}
                >
                  <h3>Food Aid Distribution</h3>
                  <p>📍 Sousse</p>
                  <p>📅 20 Feb 2025</p>
                </div>

                <div 
                  className="card" 
                  onClick={() => openEvent(
                    'Blood Donation',
                    'Tunis',
                    '12 Mar 2025',
                    'Large community blood donation campaign. Your donation can save lives! Come join us for this vital community health initiative. 💉'
                  )}
                >
                  <h3>Blood Donation</h3>
                  <p>📍 Tunis</p>
                  <p>📅 12 Mar 2025</p>
                </div>

                <div 
                  className="card" 
                  onClick={() => openEvent(
                    'Beach Cleanup',
                    'Bizerte',
                    '05 Apr 2025',
                    'Help us clean our beautiful coastline and raise environmental awareness. Bring your friends and let\'s make our beaches shine! 🌊'
                  )}
                >
                  <h3>Beach Cleanup</h3>
                  <p>📍 Bizerte</p>
                  <p>📅 05 Apr 2025</p>
                </div>
              </div>
            </div>

            {/* Event Details */}
            {selectedEvent && (
              <div id="eventDetails" className="fade-in visible" style={{ display: 'block' }}>
                <button className="back-btn" onClick={goBack}>← Back to events</button>

                <h2 id="detailTitle">{selectedEvent.title}</h2>
                <p><strong>Location:</strong> <span id="detailLocation">{selectedEvent.location}</span></p>
                <p><strong>Date:</strong> <span id="detailDate">{selectedEvent.date}</span></p>
                <p id="detailDescription" style={{ marginTop: '1rem', marginBottom: '2rem' }}>
                  {selectedEvent.description}
                </p>

                <h3>Join This Amazing Event! 🎉</h3>
                <p style={{ color: 'var(--text)', marginBottom: '1rem' }}>
                  Fill in your details and we'll save you a spot. Can't wait to see you there!
                </p>
                
                <form onSubmit={submitForm}>
                  <input 
                    type="text" 
                    id="firstName" 
                    placeholder="Your First Name" 
                    required 
                    value={formData.firstName}
                    onChange={(e) => setFormData({...formData, firstName: e.target.value})}
                  />
                  <input 
                    type="text" 
                    id="lastName" 
                    placeholder="Your Last Name" 
                    required 
                    value={formData.lastName}
                    onChange={(e) => setFormData({...formData, lastName: e.target.value})}
                  />
                  <input 
                    type="email" 
                    id="userEmail" 
                    placeholder="your@email.com" 
                    required 
                    value={formData.email}
                    onChange={(e) => setFormData({...formData, email: e.target.value})}
                  />
                  <input 
                    type="tel" 
                    id="userPhone" 
                    placeholder="+216 XX XXX XXX" 
                    required 
                    value={formData.phone}
                    onChange={(e) => setFormData({...formData, phone: e.target.value})}
                  />

                  <button type="submit" className="submit">Count Me In! ✨</button>
                </form>

                {showConfirmation && (
                  <div id="confirmation" style={{ display: 'block', marginTop: '1.5rem' }}>
                    <div style={{ textAlign: 'center' }}>
                      <div style={{ fontSize: '2.5rem', marginBottom: '0.5rem' }}>🎉</div>
                      <h4 style={{ color: '#0b8f6a', marginBottom: '0.5rem' }}>You're All Set!</h4>
                      <p style={{ color: '#0b8f6a' }}>
                        Thank you for joining! We've sent a confirmation email with all the details. See you there! 💙
                      </p>
                    </div>
                  </div>
                )}
              </div>
            )}
          </main>
        </div>
      </div>

      <style>{`
        .event-marquee {
          width: 100%;
          max-width: 1250px;
          margin: 20px auto 20px;
          background: #7B8AB6;
          color: white;
          padding: 12px 0;
          border-radius: 10px;
          overflow: hidden;
          position: relative;
        }

        .marquee-track {
          display: flex;
          width: max-content;
          animation: marquee-loop 12s linear infinite;
        }

        .marquee-content {
          display: inline-block;
          white-space: nowrap;
          font-size: 18px;
          font-weight: 600;
          padding-right: 60px;
        }

        @keyframes marquee-loop {
          0% { transform: translateX(0); }
          100% { transform: translateX(-50%); }
        }

        .container {
          max-width: 1200px;
          margin: 22px auto;
          padding: 0 16px;
        }

        .hero-section {
          display: grid;
          grid-template-columns: 1.3fr 0.7fr;
          gap: 20px;
          background: var(--card-bg, #ffffff);
          border: 1px solid var(--border, #E3CED1);
          border-radius: 22px;
          padding: 22px 26px;
          box-shadow: 0 8px 20px rgba(24, 24, 43, 0.06);
          border-top: 4px solid #cccae6;
        }

        .hero-input {
          display: flex;
          gap: 8px;
          align-items: center;
          background: var(--form-bg, #ffffff);
          border: 1px solid var(--border, #E3CED1);
          border-radius: 14px;
          padding: 10px 12px;
        }

        .hero-input input {
          flex: 1;
          border: none;
          outline: none;
          padding: 10px 8px;
          font-size: 15px;
          background: transparent;
        }

        .hero-input button {
          background: #7B8AB6;
          color: #fff;
          border: none;
          border-radius: 12px;
          padding: 10px 18px;
          box-shadow: 0 6px 14px rgba(119, 92, 255, 0.15);
          font-weight: 600;
          cursor: pointer;
        }

        .trending {
          margin-top: 22px;
          background: var(--card-bg, #ffffff);
          border: 1px solid var(--border, #E3CED1);
          border-radius: 22px;
          padding: 20px 26px;
          box-shadow: 0 8px 20px rgba(24, 24, 43, 0.06);
        }

        .trending h3 {
          text-align: center;
          font-size: 20px;
          margin-bottom: 14px !important;
          color: #1e3a5f;
        }

        .trending-center .topics-scroll {
          justify-content: center;
        }

        .topics-scroll {
          display: flex;
          gap: 10px;
          flex-wrap: wrap;
          justify-content: center;
        }

        .topic-item {
          background: #f1effa;
          padding: 10px 20px;
          border-radius: 999px;
          font-weight: 600;
          font-size: 15px;
          border: 1px solid var(--border, #E3CED1);
          color: #696a90;
          transition: 0.25s;
          cursor: pointer;
        }

        .topic-item a {
          text-decoration: none;
          color: inherit;
        }

        .topic-item:hover {
          background: #e8e4ff;
          color: #7B8AB6;
          transform: translateY(-3px);
          box-shadow: 0 6px 12px rgba(119, 92, 255, 0.15);
        }

        .layout {
          margin-top: 22px;
          display: grid;
          grid-template-columns: 320px minmax(0, 1fr);
          gap: 22px;
        }

        .sidebar {
          display: flex;
          flex-direction: column;
          gap: 16px;
        }

        .about-card, .tag-card {
          background: var(--card-bg, #ffffff);
          border: 1px solid var(--border, #E3CED1);
          border-radius: 22px;
          padding: 16px;
          box-shadow: 0 8px 20px rgba(24, 24, 43, 0.06);
        }

        .tags {
          display: flex;
          flex-wrap: wrap;
          gap: 8px;
          margin-top: 8px;
        }

        .tags span {
          background: #e8e4ff;
          padding: 10px 20px;
          border-radius: 999px;
          font-weight: 600;
          font-size: 15px;
          border: 1px solid var(--border, #E3CED1);
          color: #7B8AB6;
          transform: translateY(-3px);
          box-shadow: 0 6px 12px rgba(119, 92, 255, 0.15);
          cursor: pointer;
          transition: 0.25s;
        }

        .main-content {
          flex: 1;
        }

        .main-content h2 {
          margin: 0 0 10px;
          color: #1e3a5f;
        }

        .event-list {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
          gap: 18px;
        }

        .card {
          background: var(--card-bg, #ffffff);
          border: 1px solid var(--border, #E3CED1);
          border-radius: 20px;
          padding: 18px;
          cursor: pointer;
          box-shadow: 0 12px 28px rgba(24, 24, 43, 0.08);
          transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
          transform: translateY(-6px) scale(1.02);
          box-shadow: 0 16px 30px rgba(24, 24, 43, 0.12);
        }

        .card h3 {
          margin: 0 0 6px;
          color: #1e3a5f;
        }

        .card p {
          color: #5b7ba4;
          margin: 4px 0;
        }

        #eventDetails {
          background: var(--card-bg, #ffffff);
          border: 1px solid var(--border, #E3CED1);
          border-radius: 22px;
          padding: 22px;
          box-shadow: 0 12px 28px rgba(24, 24, 43, 0.08);
        }

        .back-btn {
          background: linear-gradient(135deg, #7B8AB6, #A09EBB);
          color: #fff;
          border: none;
          padding: 10px 14px;
          border-radius: 10px;
          cursor: pointer;
          box-shadow: 0 12px 24px rgba(119, 92, 255, 0.25);
          margin-bottom: 10px;
        }

        form {
          display: flex;
          flex-direction: column;
          gap: 12px;
          margin-top: 14px;
        }

        input, select {
          background: var(--form-bg, #fbfbff);
          border: 1px solid var(--border, #e8e8f3);
          padding: 12px;
          border-radius: 12px;
          outline: none;
          font-family: 'Poppins', sans-serif;
        }

        input:focus {
          border-color: #7B8AB6;
        }

        button.submit {
          background: #7B8AB6;
          color: #fff;
          border: none;
          border-radius: 12px;
          padding: 12px;
          cursor: pointer;
          font-weight: 700;
          font-family: 'Poppins', sans-serif;
        }

        #confirmation {
          display: none;
          margin-top: 14px;
          background: #e8fff7;
          border: 1px solid #c9f0e5;
          color: #0b8f6a;
          border-radius: 14px;
          padding: 16px;
          box-shadow: 0 12px 28px rgba(24, 24, 43, 0.08);
        }

        .fade-in {
          opacity: 0;
          transform: translateY(30px);
          transition: all 0.8s ease;
        }

        .fade-in.visible {
          opacity: 1;
          transform: translateY(0);
        }

        @media (max-width: 980px) {
          .hero-section {
            grid-template-columns: 1fr;
          }
          .layout {
            grid-template-columns: 1fr;
          }
        }
      `}</style>
    </div>
  );
}
