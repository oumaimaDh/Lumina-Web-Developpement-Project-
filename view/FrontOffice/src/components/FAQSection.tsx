import { motion } from "motion/react";
import { useState } from "react";
import { ChevronDown, Heart, Star, Users } from "lucide-react";

export function FAQSection() {
  const [openIndex, setOpenIndex] = useState<number | null>(null);

  const faqs = [
    {
      question: "How do I get started? 🚀",
      answer: "It's super easy! Just browse our initiatives, find one that excites you, and click to register. We'll send you all the details via email. No complicated forms – promise!"
    },
    {
      question: "Can I bring my friends along? 👥",
      answer: "Absolutely! The more the merrier! Volunteering with friends is one of the best ways to make it even more fun and meaningful."
    },
    {
      question: "What if I've never volunteered before? 🤔",
      answer: "Perfect! Most of our volunteers are first-timers too. Every initiative has friendly organizers who'll guide you through everything. Just bring your enthusiasm!"
    },
    {
      question: "Do I need to commit to a schedule? ⏰",
      answer: "Not at all! Join initiatives when it works for you. Whether you have a few hours or want to make it a regular thing, you're welcome here!"
    }
  ];

  return (
    <section className="mt-16">
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Left Column - Getting Started */}
        <div className="relative bg-gradient-to-br from-white to-[#f5f0ed] rounded-[3rem] p-8 md:p-12 shadow-lg border-2 border-[#8195b8]/10">
          <div className="inline-flex items-center gap-2 bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white px-5 py-2 rounded-full mb-6">
            <Star className="w-4 h-4 fill-white" />
            <span className="text-sm">Getting Started</span>
          </div>
          
          <h3 className="text-[#1e3a5f] mb-3">Your Journey Begins Here! 🌟</h3>
          <p className="text-[#5b7ba4] mb-8">
            Three simple steps to start making a difference in your community. It's easier than ordering coffee!
          </p>
          
          <div className="space-y-6">
            <div className="flex gap-4">
              <div className="bg-gradient-to-br from-[#6b7da8] to-[#8195b8] text-white w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <span className="text-xl">1</span>
              </div>
              <div>
                <p className="text-[#1e3a5f] mb-2">Create Your Free Account ✨</p>
                <p className="text-sm text-[#5b7ba4]">Quick sign-up – takes less than a minute!</p>
              </div>
            </div>
            
            <div className="flex gap-4">
              <div className="bg-gradient-to-br from-[#6b7da8] to-[#8195b8] text-white w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <span className="text-xl">2</span>
              </div>
              <div>
                <p className="text-[#1e3a5f] mb-2">Find What You Love ❤️</p>
                <p className="text-sm text-[#5b7ba4]">Browse projects that match your interests</p>
              </div>
            </div>
            
            <div className="flex gap-4">
              <div className="bg-gradient-to-br from-[#6b7da8] to-[#8195b8] text-white w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <span className="text-xl">3</span>
              </div>
              <div>
                <p className="text-[#1e3a5f] mb-2">Make Magic Happen! 🎉</p>
                <p className="text-sm text-[#5b7ba4]">Show up, have fun, make friends</p>
              </div>
            </div>
          </div>

          {/* Decorative blob */}
          <div className="absolute bottom-0 right-0 w-32 h-32 bg-[#8195b8]/10 rounded-full blur-2xl" />
        </div>

        {/* Right Column - FAQs */}
        <div className="relative bg-gradient-to-br from-white to-[#f5f0ed] rounded-[3rem] p-8 md:p-12 shadow-lg border-2 border-[#8195b8]/10">
          <div className="inline-flex items-center gap-2 bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white px-5 py-2 rounded-full mb-6">
            <Heart className="w-4 h-4 fill-white" />
            <span className="text-sm">Common Questions</span>
          </div>
          
          <h3 className="text-[#1e3a5f] mb-3">We've Got Answers! 💬</h3>
          <p className="text-[#5b7ba4] mb-6">
            Still curious? Here are the questions we hear most often
          </p>
          
          <div className="space-y-3">
            {faqs.map((faq, idx) => (
              <div
                key={idx}
                className="bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden border-2 border-[#8195b8]/20 hover:border-[#8195b8] transition-colors"
              >
                <button
                  onClick={() => setOpenIndex(openIndex === idx ? null : idx)}
                  className="w-full flex items-center justify-between p-4 text-left hover:bg-white/80 transition-colors"
                >
                  <span className="text-[#1e3a5f]">{faq.question}</span>
                  <ChevronDown
                    className={`w-5 h-5 text-[#5b7ba4] transition-transform flex-shrink-0 ml-2 ${
                      openIndex === idx ? "rotate-180" : ""
                    }`}
                  />
                </button>
                
                {openIndex === idx && (
                  <motion.div
                    initial={{ height: 0, opacity: 0 }}
                    animate={{ height: "auto", opacity: 1 }}
                    exit={{ height: 0, opacity: 0 }}
                    className="px-4 pb-4"
                  >
                    <p className="text-[#5b7ba4] text-sm leading-relaxed bg-[#f5f0ed]/50 rounded-xl p-3">
                      {faq.answer}
                    </p>
                  </motion.div>
                )}
              </div>
            ))}
          </div>

          {/* Still have questions */}
          <div className="mt-6 text-center bg-gradient-to-r from-[#8195b8]/10 to-[#6b7da8]/10 rounded-2xl p-4">
            <p className="text-[#5b7ba4] text-sm mb-2">Still have questions?</p>
            <button className="text-[#1e3a5f] hover:text-[#5b7ba4] transition-colors underline">
              Chat with us anytime! We're friendly, we promise 😊
            </button>
          </div>
        </div>
      </div>
    </section>
  );
}